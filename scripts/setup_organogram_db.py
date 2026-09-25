import psycopg2

def setup():
    conn = psycopg2.connect(host='127.0.0.1', port=5432, dbname='ncs_db', user='postgres', password='')
    conn.autocommit = True
    cur = conn.cursor()

    # 1. Create Organogram Tables
    cur.execute("""
    CREATE TABLE IF NOT EXISTS ncs_organogram_nodes (
        id SERIAL PRIMARY KEY,
        role_id INTEGER NOT NULL UNIQUE,
        department_id INTEGER NOT NULL,
        parent_node_id INTEGER DEFAULT NULL,
        tier_level INTEGER DEFAULT 3,
        pos_x DOUBLE PRECISION DEFAULT 100.0,
        pos_y DOUBLE PRECISION DEFAULT 100.0,
        is_apex SMALLINT DEFAULT 0,
        approval_limit_ugx NUMERIC(18,2) DEFAULT 0.00,
        created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS ncs_organogram_connectors (
        id SERIAL PRIMARY KEY,
        from_node_id INTEGER NOT NULL,
        to_node_id INTEGER NOT NULL,
        workflow_type VARCHAR(50) DEFAULT 'general',
        routing_condition TEXT DEFAULT NULL,
        created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS ncs_approval_workflows (
        id SERIAL PRIMARY KEY,
        module VARCHAR(50) NOT NULL,
        record_id INTEGER NOT NULL,
        requester_id INTEGER NOT NULL,
        start_node_id INTEGER NOT NULL,
        current_node_id INTEGER NOT NULL,
        status VARCHAR(30) DEFAULT 'pending',
        current_step INTEGER DEFAULT 1,
        total_steps INTEGER DEFAULT 1,
        step_history TEXT DEFAULT NULL,
        created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
    );

    GRANT ALL PRIVILEGES ON TABLE ncs_organogram_nodes TO ncs_user;
    GRANT ALL PRIVILEGES ON SEQUENCE ncs_organogram_nodes_id_seq TO ncs_user;
    GRANT ALL PRIVILEGES ON TABLE ncs_organogram_connectors TO ncs_user;
    GRANT ALL PRIVILEGES ON SEQUENCE ncs_organogram_connectors_id_seq TO ncs_user;
    GRANT ALL PRIVILEGES ON TABLE ncs_approval_workflows TO ncs_user;
    GRANT ALL PRIVILEGES ON SEQUENCE ncs_approval_workflows_id_seq TO ncs_user;
    """)
    print("Organogram tables verified/created.")

    # 2. Check and seed standard NCS departments if empty
    cur.execute("SELECT COUNT(*) FROM ncs_departments WHERE deleted=0")
    dept_count = cur.fetchone()[0]
    dept_map = {}

    if dept_count == 0:
        departments = [
            ("General Secretariat & Executive", "GS", "Office of the General Secretary and Executive Authority", 1),
            ("Accounting & Finance", "FIN", "Financial Management, Vote Appropriations, and Subventions", 1),
            ("Technical & Sports Development", "TSD", "National Federations, High Performance, and Competitions", 1),
            ("Facilities & Venue Operations", "FAC", "Lugogo Sports Complex and National Venues Administration", 1),
            ("Procurement & Disposal Unit", "PDU", "Statutory Procurement Form 5, Tendering & PPDA Compliance", 1),
            ("Internal Audit Department", "AUD", "Statutory Audit, Spot-Checks, Internal Controls & Verification", 1),
            ("Legal & Compliance Department", "LEG", "Contracts Vault, Federation Arbitration & Regulatory Oversight", 1),
            ("Engineering & Maintenance", "ENG", "Civil Works, Machinery, Power & Capital Infrastructure", 1),
            ("Human Resource & Administration", "HRA", "Talent Management, Appraisals, Payroll & Establishment", 1),
            ("ICT & Media Communications", "ICT", "Digital Infrastructure, Broadcast Media & Systems", 1),
        ]
        for title, code, desc, head_id in departments:
            cur.execute("""
                INSERT INTO ncs_departments (title, code, description, head_id, deleted)
                VALUES (%s, %s, %s, %s, 0) RETURNING id, code
            """, (title, code, desc, head_id))
            row = cur.fetchone()
            dept_map[row[1]] = row[0]
        print(f"Seeded {len(departments)} NCS departments.")
    else:
        cur.execute("SELECT id, code FROM ncs_departments WHERE deleted=0")
        for r in cur.fetchall():
            dept_map[r[1]] = r[0]
        print(f"Existing departments found: {dept_count}")

    # 3. Check and seed standard NCS roles (offices) if empty
    cur.execute("SELECT COUNT(*) FROM ncs_roles WHERE deleted=0")
    roles_count = cur.fetchone()[0]

    if roles_count == 0:
        # (title, dept_code, rank)
        roles = [
            # Executive Apex
            ("General Secretary (Accounting Officer)", "GS", 1),
            # Branch Heads / Directors (Rank 2)
            ("Head of Finance & Accounting", "FIN", 2),
            ("Head of Technical & Sports Development", "TSD", 2),
            ("Facilities & Venues Manager", "FAC", 2),
            ("Head of Procurement (PDU)", "PDU", 2),
            ("Chief Internal Auditor", "AUD", 2),
            ("Legal Counsel & Head of Compliance", "LEG", 2),
            ("Chief Maintenance Engineer", "ENG", 2),
            ("Head of Human Resources", "HRA", 2),
            ("Principal ICT & Media Officer", "ICT", 2),
            # Senior Supervisory Offices (Rank 3)
            ("Senior Accountant", "FIN", 3),
            ("Senior Sports Officer", "TSD", 3),
            ("Senior Estate & Facility Supervisor", "FAC", 3),
            ("Senior Procurement Officer", "PDU", 3),
            ("Senior Internal Auditor", "AUD", 3),
            ("Senior Human Resource Officer", "HRA", 3),
            ("Senior Civil & Electrical Engineer", "ENG", 3),
            ("Systems & Database Administrator", "ICT", 3),
            # Line Operational Officers (Rank 4)
            ("Project Accountant / Grants Officer", "FIN", 4),
            ("Federations Liaison Officer", "TSD", 4),
            ("Hostel Warden / Custodian", "FAC", 4),
            ("Procurement Officer", "PDU", 4),
            ("Audit Assistant", "AUD", 4),
            ("HR Records Officer", "HRA", 4),
            ("Maintenance Technician", "ENG", 4),
            ("ICT Support Technician", "ICT", 4),
            # Support Offices (Rank 5)
            ("Accounts Assistant / Cashier", "FIN", 5),
            ("Sports Assistant", "TSD", 5),
            ("Venue Booking Clerk", "FAC", 5),
        ]

        for title, dept_code, rank in roles:
            dept_id = dept_map.get(dept_code, 1)
            cur.execute("""
                INSERT INTO ncs_roles (title, department_id, rank, permissions, deleted)
                VALUES (%s, %s, %s, '', 0)
            """, (title, dept_id, rank))
        print(f"Seeded {len(roles)} NCS roles/offices across departments.")
    else:
        print(f"Existing roles found: {roles_count}")

    # 4. Check initial organogram nodes
    cur.execute("SELECT COUNT(*) FROM ncs_organogram_nodes")
    node_count = cur.fetchone()[0]
    print(f"Current organogram nodes: {node_count}")

    conn.close()

if __name__ == '__main__':
    setup()
