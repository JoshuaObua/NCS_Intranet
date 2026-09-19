"""Reconcile source workbook, checked-in seed, and optional read-only local DB export.
Run from repository root; requires openpyxl. The source's literal hash value is
reported as an exception, never silently claimed to be a known zero valuation.
"""
import json
import re
from decimal import Decimal, InvalidOperation
from pathlib import Path
import openpyxl

OUT = Path(__file__).resolve().parent
ROOT = OUT.parents[2]
workbook = openpyxl.load_workbook(ROOT / 'Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx', read_only=True, data_only=True)
columns = ['INTERFACE_LINE_NUMBER', 'ASSET_BOOK', 'ASSET_NUMBER', 'TAG_NUMBER', 'ASSET_DESCRIPTION', 'ASSET_CATEGORY_SEGMENT1', 'ASSET_CATEGORY_SEGMENT3', 'ASSET_CATEGORY_SEGMENT4', 'ASSET_UNITS']
seed = {}
for line in (ROOT / 'backend/migrations/034_seed_fixed_assets.sql').read_text().splitlines():
    if line.startswith('  ('):
        fields = re.findall(r"'((?:[^']|'')*)'|(-?\d+(?:\.\d+)?)", line)
        values = [s.replace("''", "'") if s else number for s, number in fields]
        seed[values[2]] = values
sheets, exceptions, differences, fixture, expected_records = [], [], [], [], {}
for sheet in workbook:
    if sheet.title == 'PIVOT TABLE':
        continue
    rows = list(sheet.iter_rows(max_col=13, values_only=True))
    total_fb, total_adjusted, count = Decimal(0), Decimal(0), 0
    for row in rows[1:]:
        source = dict(zip(rows[0], row))
        code = source.get('ASSET_NUMBER')
        if not code:
            continue
        count += 1
        try:
            cost = Decimal(str(source['FB_COST']))
        except InvalidOperation:
            exceptions.append({'asset': code, 'field': 'FB_COST', 'raw': source['FB_COST'], 'seed_convention': '0; unresolved source amount'})
            cost = Decimal(0)
        adjusted = Decimal(str(source.get('ADJUSTED COST') or cost))
        total_fb += cost
        total_adjusted += adjusted
        values = [str(source[k]).strip() for k in columns] + [str(cost), str(adjusted), str(source['DATE_PLACED_IN_SERVICE']).replace('/', '-')]
        expected_records[code] = values
        actual = seed.get(code)
        if actual is None:
            differences.append({'asset': code, 'missing': True})
            continue
        for index, expected in enumerate(values):
            same = Decimal(expected) == Decimal(actual[index]) if index in (8, 9, 10) else expected == actual[index]
            if not same:
                differences.append({'asset': code, 'column_index': index, 'workbook': expected, 'seed': actual[index]})
        if actual[13] != sheet.title:
            differences.append({'asset': code, 'worksheet_source': actual[13], 'expected': sheet.title})
        fixture.append(dict(id=code, asset_number=code, tag_number=values[3], asset_description=values[4], category_segment1=values[5], category_segment3=values[6], category_segment4=values[7], asset_units=int(values[8]), fb_cost=float(cost), adjusted_cost=float(adjusted), net_book_value=float(adjusted), verification_status='UNVERIFIED', worksheet_source=sheet.title))
    sheets.append({'worksheet': sheet.title, 'count': count, 'fb_cost': str(total_fb), 'adjusted_cost': str(total_adjusted)})
extra = sorted(set(seed) - set(expected_records))
pivot = [list(row) for row in workbook['PIVOT TABLE'].iter_rows(max_col=3, values_only=True) if any(x is not None for x in row)]
source_total = sum(Decimal(s['fb_cost']) for s in sheets)
result = {'scope': 'Workbook versus migration 034; hash source exception reported explicitly', 'sheets': sheets, 'seed_records': len(seed), 'source_exceptions': exceptions, 'differences': differences, 'extra_seed_assets': extra, 'total_fb_cost': str(source_total), 'total_adjusted_cost': str(sum(Decimal(s['adjusted_cost']) for s in sheets)), 'cached_pivot': {'nonempty_rows_including_header': len(pivot), 'grand_total': pivot[-1][2], 'source_minus_cached_pivot': str(source_total - Decimal(str(pivot[-1][2]))), 'land_cached_total': next(row[2] for row in pivot if row[0] == 'LAND'), 'status': 'STALE: cached pivot differs from current source sheets'}}
(OUT / 'workbook-parity.json').write_text(json.dumps(result, indent=2) + '\n')
(OUT / 'workbook-fixture.json').write_text(json.dumps(fixture, indent=2) + '\n')
export = OUT / 'local-database.jsonl'
if export.exists():
    metadata, body = export.read_text().split('\n', 1)
    records = json.loads(body)
    by_code = {record['asset_number']: record for record in records}
    db_fields = ['interface_line_number', 'asset_book', 'asset_number', 'tag_number', 'asset_description', 'category_segment1', 'category_segment3', 'category_segment4', 'asset_units', 'fb_cost', 'adjusted_cost', 'date_placed_in_service']
    db_differences = []
    for code, expected in expected_records.items():
        actual = by_code.get(code)
        for index, field in enumerate(db_fields):
            value = str(actual.get(field)) if actual else None
            equal = value is not None and (Decimal(expected[index]) == Decimal(value) if index in (8, 9, 10) else expected[index] == value)
            if not equal:
                db_differences.append({'asset': code, 'field': field, 'expected': expected[index], 'actual': value})
    for a in fixture:
        actual = by_code.get(a['asset_number'])
        if actual and actual['worksheet_source'] != a['worksheet_source']:
            db_differences.append({'asset': a['asset_number'], 'field': 'worksheet_source'})
    (OUT / 'local-database-parity.json').write_text(json.dumps({'scope': 'Read-only LOCAL Docker database, not remote VPS', 'metadata': json.loads(metadata), 'expected_records': len(expected_records), 'database_records': len(records), 'differences': db_differences, 'extra_database_assets': sorted(set(by_code) - set(expected_records))}, indent=2) + '\n')
    assert not db_differences and set(by_code) == set(expected_records)
print(json.dumps({'seed_records': len(seed), 'differences': len(differences), 'source_exceptions': exceptions, 'cached_pivot': result['cached_pivot']}, indent=2))
assert not differences and not extra
