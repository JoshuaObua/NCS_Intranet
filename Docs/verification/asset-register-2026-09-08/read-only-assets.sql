BEGIN READ ONLY;
SELECT json_build_object('database',current_database(),'server_version',current_setting('server_version'),'assets',(SELECT count(*) FROM fixed_assets),'logs',(SELECT count(*) FROM asset_transaction_logs),'fb_cost',(SELECT sum(fb_cost) FROM fixed_assets),'adjusted_cost',(SELECT sum(adjusted_cost) FROM fixed_assets),'accumulated_depreciation',(SELECT sum(accumulated_depreciation) FROM fixed_assets));
SELECT json_agg(a) FROM (SELECT id,interface_line_number,asset_book,asset_number,tag_number,asset_description,category_segment1,category_segment3,category_segment4,asset_units,fb_cost,adjusted_cost,date_placed_in_service,useful_life_years,worksheet_source,accumulated_depreciation,status FROM fixed_assets ORDER BY asset_number) a;
COMMIT;
