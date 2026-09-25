# Comprehensive Architectural Plan: NCS Organogram & Bottom-to-Top Approval Workflow Engine

**System:** National Council of Sports (NCS) Intranet  
**Component:** Organization Chart Diagram (Organogram) & Approval Routing Engine  
**Author:** AI Pair Programmer & System Architect  
**Status:** Architecture & Implementation Blueprint  

---

## 1. Executive Summary & Conceptual Paradigm

The NCS Intranet Organogram is not merely an organizational visualization tool; it is the **authoritative governance graph** that controls how documents, requisitions, subventions, budgets, and memos flow through the National Council of Sports.

### Fundamental System Equivalence
* **Department = Branch**: A distinct organizational branch (e.g., General Secretariat, Accounting & Finance, Technical & Sports Development, Facilities & Venues, Internal Audit, Legal & Compliance, Procurement, Engineering, ICT & Media, HR).
* **Role = Office**: A functional post/station within the hierarchy (e.g., General Secretary, Head of Accounting, Internal Auditor, Senior Sports Officer, Facility Manager, Procurement Officer).
* **Staff Member = Office Holder**: An active user occupying an office.
* **Approval Flow = Bottom-to-Top**: Personnel at lower tiers initiate requests (e.g., leave, expense vouchers, Form 5 procurement requests, stores requisitions, memos), which recursively route upward through designated supervisory offices until reaching the final sanctioning authority (Accounting Officer / General Secretary / Board).

```
===================================================================================
                       HIERARCHICAL GOVERNANCE LADDER
===================================================================================
 Tier 1 (Apex):          [ Board of Directors / Ministerial Liaison ]
                                      ▲
                                      │ Final Policy & Governance Ratification
                                      │
 Tier 2 (Accounting):    [ General Secretary (Accounting Officer) ]
                                      ▲
                                      │ Statutory Approval, Vote Commitments & Sign-offs
                                      │
 Tier 3 (Branch Head):   [ Heads of Departments (Directors / Managers) ]
                                      ▲
                                      │ Technical Endorsement & Operational Clearance
                                      │
 Tier 4 (Supervisory):   [ Senior Officers / Section Leads ]
                                      ▲
                                      │ Immediate Review, Verification & Recommendation
                                      │
 Tier 5 (Initiators):    [ Officers / Assistants / Field Technicians ]
                                      ▲
                                      │ Document / Request Initiation (Bottom-to-Top)
===================================================================================
```

---

## 2. Interactive Diagram & Drag-and-Drop Canvas Design

### 2.1 Workspace Layout
The screen layout is divided into two distinct zones:
1. **Left/Center: Interactive Organogram Canvas**:
   * Pan, zoom (25% to 200%), fit-to-screen, and reset controls.
   * Infinite/expandable grid background with visual hierarchy guides.
   * Auto-layout button (hierarchical top-down layout).
   * Node cards indicating:
     * Office Title (Role) & Department (Branch)
     * Rank/Tier Badge (Tier 1 = Executive down to Tier 5 = Assistant)
     * Office Holder avatar & name
     * Reporting parent indicator
     * Interactive connector handles (top handle for parent, bottom handle for subordinates)
     * Delete/Unlink button (which removes the node from canvas and **returns it to the right palette**).
   * **Dynamic Bezier Arrow Connectors**:
     * High-performance SVG string curves linking subordinate offices to superior offices.
     * Animated directional arrows pointing to the workflow approval direction (upward towards the superior).
2. **Right Palette: Unplaced Branches & Offices Pool**:
   * Search filter & department accordion.
   * Categorized into **Branches (Departments)** and **Offices (Roles)**.
   * Once an item is dragged onto the canvas, **it is removed completely from the palette** to guarantee strict zero-duplication invariants.
   * If a node is deleted from the canvas, it is restored back to the palette immediately.

```
+-------------------------------------------------------------------+------------------------+
|  ORGANOGRAM & APPROVAL WORKFLOW DESIGNER                          |  BRANCHES & OFFICES    |
|  [ Auto-Layout ] [ Save Diagram ] [ Zoom In/Out ] [ Test Flow ]   |  [ Search Office... ]  |
+-------------------------------------------------------------------+------------------------+
|                                                                   | ▼ BRANCH: ACCOUNTING   |
|         +----------------------------------+                      |   [+] Accounts Asst    |
|         | Office: General Secretary        |                      |   [+] Cashier          |
|         | Branch: General Secretariat      |                      |                        |
|         | Rank: Tier 1 (Apex)              |                      | ▼ BRANCH: SPORTS DEV   |
|         +-----------------+----------------+                      |   [+] Technical Officer|
|                           ▲                                       |   [+] Coach Coordinator|
|                           │ (Upward Approval Flow)                |                        |
|                           │                                       | ▼ BRANCH: FACILITIES   |
|         +-----------------+----------------+                      |   [+] Hostel Custodian |
|         | Office: Head of Accounting       |                      |                        |
|         | Branch: Accounting Department    |                      | [ DRAG ITEM TO CANVAS ]|
|         | Rank: Tier 2 (Director)          |                      | Items move permanently |
|         +-----------------+----------------+                      | to prevent duplicates! |
|                           ▲                                       |                        |
|                           │                                       |                        |
|         +-----------------+----------------+                      |                        |
|         | Office: Senior Accountant        |                      |                        |
|         | Branch: Accounting Department    |                      |                        |
|         | Rank: Tier 3 (Senior)            |                      |                        |
|         +----------------------------------+                      |                        |
|                                                                   |                        |
+-------------------------------------------------------------------+------------------------+
```

---

## 3. Database Schema Architecture

To store the diagram layout, organizational hierarchy, and dynamic approval rules, we introduce three core tables:

### 3.1 Table: `ncs_organogram_nodes`
Stores the placed offices (roles) and their coordinates on the canvas:
* `id` (SERIAL PRIMARY KEY)
* `role_id` (INT NOT NULL UNIQUE, references `ncs_roles(id)`)
* `department_id` (INT NOT NULL, references `ncs_departments(id)`)
* `parent_node_id` (INT NULL, self-reference to `ncs_organogram_nodes(id)`)
* `tier_level` (INT NOT NULL DEFAULT 3) - 1: Apex/AO, 2: Director/Head, 3: Senior, 4: Officer, 5: Support
* `pos_x` (FLOAT NOT NULL DEFAULT 100.0)
* `pos_y` (FLOAT NOT NULL DEFAULT 100.0)
* `is_apex` (SMALLINT DEFAULT 0)
* `approval_limit_ugx` (NUMERIC(18,2) DEFAULT 0.00) - Maximum financial sanction limit for this office
* `created_at`, `updated_at` (TIMESTAMP)

### 3.2 Table: `ncs_organogram_connectors`
Tracks explicit links and flow rules between offices:
* `id` (SERIAL PRIMARY KEY)
* `from_node_id` (INT NOT NULL, references `ncs_organogram_nodes(id)`)
* `to_node_id` (INT NOT NULL, references `ncs_organogram_nodes(id)`)
* `workflow_type` (VARCHAR(50) DEFAULT 'general') - `general`, `procurement`, `expense`, `leave`, `memo`
* `routing_condition` (TEXT NULL) - e.g., JSON conditions (amount thresholds, vote-heads)

### 3.3 Table: `ncs_approval_requests` & `ncs_approval_steps`
A universal engine table where any intranet module can dispatch a document for organogram-driven approval:
* `module` (VARCHAR(50)) - e.g., `expenses`, `procurement_form_5`, `leave`, `store_requisitions`
* `record_id` (INT) - ID of the underlying record
* `current_node_id` (INT) - Office currently tasked with approval
* `status` (VARCHAR(30)) - `pending`, `approved`, `rejected`, `referred_back`
* `approval_history` (JSONB) - Trail of timestamps, signers, ranks, and remarks

---

## 4. Bottom-to-Top Approval Propagation Algorithm

When an employee initiates a request, the system executes the following resolution pipeline:

```
[ Step 1: Identify Requester Office ]
   -> Look up login_user's role_id and department_id in ncs_organogram_nodes
   -> Obtain start_node (Requester's Node)

[ Step 2: Traverse Parent Nodes Upward ]
   -> While parent_node_id IS NOT NULL:
       -> Next Approver = parent_node_id
       -> Check financial sanction limit (if amount > limit, escalate to next higher parent)
       -> Append to Approval Sequence Chain

[ Step 3: Terminate at Apex / Designated Authority ]
   -> Highest parent with is_apex = 1 (e.g. General Secretary / Accounting Officer)
   -> Emit finalized linear approval chain [Level 1 -> Level 2 -> Level 3 -> Final]

[ Step 4: Notifications & Dispatch ]
   -> Alert Level 1 office holder via Intranet notifications & email
   -> Unlock digital signature / approval stamp on Level 1 sign-off
   -> Advance token to Level 2 until chain completes
```

---

## 5. UI/UX Implementation Details

1. **Connector Line Engine**:
   * Uses SVG `<path>` elements with cubic Bezier interpolation:
     `d="M ${startX} ${startY} C ${startX} ${midY}, ${endX} ${midY}, ${endX} ${endY}"`
   * Directional arrow markers defined in `<defs><marker id="arrowhead" ...></marker></defs>`.
2. **Interactive Simulation Mode ("Test Flow")**:
   * Administrator can select any starting office (e.g. "Assistant Accountant") and enter a document type and value (e.g. UGX 15,000,000 Procurement).
   * The canvas dynamically highlights the entire path in vibrant gold and animated pulsating lines from bottom to top!
   * Shows every intermediate office, their name, rank, and required action.
3. **Sidebar Menu Integration**:
   * Registered in `app/Libraries/Left_menu.php` under `organogram` with icon `git-pull-request` or `share-2`.
   * Accessible by staff with administrative or management permissions.

---

## 6. Implementation Milestones

1. **Milestone 1: Database Architecture & Baseline Seed**:
   * Create `ncs_organogram_nodes`, `ncs_organogram_connectors`, and workflow tables.
   * Pre-populate standard NCS organizational departments and core roles if vacant.
2. **Milestone 2: Backend Controller & Model API**:
   * Create `Organogram.php` controller and `Organogram_model.php`.
   * Endpoints for node CRUD, position updating, connection linking, auto-layout, and approval chain calculation.
3. **Milestone 3: Modern Drag-and-Drop Canvas & Palette**:
   * Canvas view with pan, zoom, grid, drag-from-palette with permanent item transfer.
   * Auto-linking and dynamic SVG curved connectors.
   * Interactive workflow path simulation.
4. **Milestone 4: Universal Approval Routing Engine & Hook Integration**:
   * Library `app/Libraries/Approval_chain.php` to resolve approval chains for any user.
5. **Milestone 5: Verification, Automated Testing & Documentation**:
   * Unit and integration test suite testing tree traversal, cycle detection, drag-drop state, and approval dispatch.
