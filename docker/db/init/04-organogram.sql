-- Organogram/approval tables and default NCS departments and offices.
-- Mirrors scripts/setup_organogram_db.py, which is written for a local Laragon install.

CREATE TABLE IF NOT EXISTS public.ncs_organogram_nodes (
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

CREATE TABLE IF NOT EXISTS public.ncs_organogram_connectors (
    id SERIAL PRIMARY KEY,
    from_node_id INTEGER NOT NULL,
    to_node_id INTEGER NOT NULL,
    workflow_type VARCHAR(50) DEFAULT 'general',
    routing_condition TEXT DEFAULT NULL,
    created_at TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS public.ncs_approval_workflows (
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

INSERT INTO public.ncs_departments (title, code, description, head_id, deleted)
SELECT v.title, v.code, v.description, 1, 0
FROM (VALUES
    ('General Secretariat & Executive', 'GS', 'Office of the General Secretary and Executive Authority'),
    ('Accounting & Finance', 'FIN', 'Financial Management, Vote Appropriations, and Subventions'),
    ('Technical & Sports Development', 'TSD', 'National Federations, High Performance, and Competitions'),
    ('Facilities & Venue Operations', 'FAC', 'Lugogo Sports Complex and National Venues Administration'),
    ('Procurement & Disposal Unit', 'PDU', 'Statutory Procurement Form 5, Tendering & PPDA Compliance'),
    ('Internal Audit Department', 'AUD', 'Statutory Audit, Spot-Checks, Internal Controls & Verification'),
    ('Legal & Compliance Department', 'LEG', 'Contracts Vault, Federation Arbitration & Regulatory Oversight'),
    ('Engineering & Maintenance', 'ENG', 'Civil Works, Machinery, Power & Capital Infrastructure'),
    ('Human Resource & Administration', 'HRA', 'Talent Management, Appraisals, Payroll & Establishment'),
    ('ICT & Media Communications', 'ICT', 'Digital Infrastructure, Broadcast Media & Systems')
) AS v(title, code, description)
WHERE NOT EXISTS (SELECT 1 FROM public.ncs_departments WHERE deleted = 0);

INSERT INTO public.ncs_roles (title, department_id, rank, permissions, deleted)
SELECT v.title, COALESCE(d.id, 1), v.rank, '', 0
FROM (VALUES
    ('General Secretary (Accounting Officer)', 'GS', 1),
    ('Head of Finance & Accounting', 'FIN', 2),
    ('Head of Technical & Sports Development', 'TSD', 2),
    ('Facilities & Venues Manager', 'FAC', 2),
    ('Head of Procurement (PDU)', 'PDU', 2),
    ('Chief Internal Auditor', 'AUD', 2),
    ('Legal Counsel & Head of Compliance', 'LEG', 2),
    ('Chief Maintenance Engineer', 'ENG', 2),
    ('Head of Human Resources', 'HRA', 2),
    ('Principal ICT & Media Officer', 'ICT', 2),
    ('Senior Accountant', 'FIN', 3),
    ('Senior Sports Officer', 'TSD', 3),
    ('Senior Estate & Facility Supervisor', 'FAC', 3),
    ('Senior Procurement Officer', 'PDU', 3),
    ('Senior Internal Auditor', 'AUD', 3),
    ('Senior Human Resource Officer', 'HRA', 3),
    ('Senior Civil & Electrical Engineer', 'ENG', 3),
    ('Systems & Database Administrator', 'ICT', 3),
    ('Project Accountant / Grants Officer', 'FIN', 4),
    ('Federations Liaison Officer', 'TSD', 4),
    ('Hostel Warden / Custodian', 'FAC', 4),
    ('Procurement Officer', 'PDU', 4),
    ('Audit Assistant', 'AUD', 4),
    ('HR Records Officer', 'HRA', 4),
    ('Maintenance Technician', 'ENG', 4),
    ('ICT Support Technician', 'ICT', 4),
    ('Accounts Assistant / Cashier', 'FIN', 5),
    ('Sports Assistant', 'TSD', 5),
    ('Venue Booking Clerk', 'FAC', 5)
) AS v(title, dept_code, rank)
LEFT JOIN public.ncs_departments d ON d.code = v.dept_code AND d.deleted = 0
WHERE NOT EXISTS (SELECT 1 FROM public.ncs_roles WHERE deleted = 0);
