# NCS Stores & Inventory Management Department Dashboard Implementation Plan
The Menu Should Have
Store & Inventory
under that
-Add new Inventory Record
-Inventory Records
-Goods Recieved Notes
-Requisitions {THis should have resons for requisuion, department that the requisition is for and the user office, the requisition is sent to this should be approved by them,}
-Issuances {This should help inventory manager assign a given set of items to a given department, where if they are alredy assigned to a given department to a specific user role they can now reissue to particular user or department iwth proepr approval workflow}
-Stock Takes & Reconciliation with Accounting,
-Obsolescence Flagging.
-Sports Equipment Pool
-Engineering Spares Management
-ICT Consumables & Light Hardware
-Office & Administrative Supplies
-Monthly Stock Reconciliation & Audit Trail
-Issuance History Tracking {This should have a prper record and track of conditiosn}
-Report  inventory or item defect
-Inventory aging
-Alerts Management {This should have a prper record and track of alerts}
-Inventory audit trail
-Audit trail for stock movement 
-Audit trail for  items reported as defective or faulty 
-Request for replacement of parts and record with audit trail
-Inventory Report for Internal Audit {This is importatn for the smooth running of the store and the department}
-Reports {This should be a comprehensive list of reports that the store should have, it should have a record of these and help in the managemnt of these, for example:Inventory Report for Internal Audit {This is importatn for the smooth running of the store and the department},}

**Compliance Standards:** Public Finance Management Act (PFMA 2015), Treasury Instructions (Stores Regulations), PPDA Asset Management Guidelines.

-Store & Inventory this should be a comprehensive list of what the store should have, it should have a record of these and help in the managemnt of these, for example:Central Warehouse Management, 
Goods Received Notes (GRN), 
Electronic Bin Cards, 
Material Requisitions & Issuances,
Sports Equipment Pool, 
Stock Takes & Reconciliation with Accounting,
 and Obsolescence Flagging. 
 the ssytem should 
The **Stores & Inventory Management Unit** maintains physical custody and inventory accounting of all stock items, sports equipment, engineering spares, stationery, and maintenance supplies belonging to the National Council of Sports. It ensures uninterrupted supply to operational departments while preventing stock loss, leakage, and obsolescence.


|               NCS STORES & INVENTORY OPERATIONAL WORKFLOW                                         |
+---------------------------------------------------------------------------------------------------+
       |                                      |                                      |
       v                                      v                                      v
+-----------------------+              +-----------------------+              +-----------------------+
| 1. GOODS RECEIPT      |              | 2. INVENTORY CONTROL  |              | 3. STOCK ISSUANCE     |
| - Delivery Note Match |              | - Electronic Bin Cards|              | - Departmental Reqs   |
| - PDU PO Verification |              | - Reorder Thresholds  |              | - Store Issue Voucher |
| - Quality Inspection  |              | - Batch & Location Log|              | - Handover Signatures |
| - Issue Electronic GRN|              | - Stock Aging & Count |              | - Automated Deduction |
+-----------------------+              +-----------------------+              +-----------------------+
       |                                      |                                      |
       +--------------------------------------+--------------------------------------+
                                              |
                                              v
                       +-----------------------------------------------+
                       | RECONCILIATION & DISPOSAL PIPELINE            |
                       | - Monthly Stock Reconciliation with Accounts  |
                       | - High-Performance Sports Gear Pool Tracking  |
                       | - Damaged / Obsolete Stock Board of Survey    |
                       | - Escalation to AGS-A & GS for Write-Offs     |
                       +-----------------------------------------------+


## 2.1 Goods Received Note (GRN) & Inward Inspection Hub
- **Procurement PO Verification:** Validates incoming supplier deliveries against approved Purchase Orders and PPDA Form 5 contracts.
- **Quality & Quantity Inspection Checklist:** Inspects technical specifications before goods acceptance.
- **Electronic GRN Generation:** Issues digital GRN with timestamp, vendor details, and inspector signature, automatically alerting Accounts for invoice matching.

### 2.2 Electronic Bin Cards & Stock Ledger
- Real-time stock balance tracking across 4 main store categories:
  1. **Sports Equipment & Competition Gear:** Balls, jerseys, nets, timing mats, boxing gloves, athletics implements.
  2. **Engineering & Maintenance Spares:** Plumbing pipes, electrical cables, LED floodlight bulbs, turf fertilizers, mower blades.
  3. **ICT Consumables & Light Hardware:** Toners, paper reams, network patch cords, power surge strips.
  4. **Office & Administrative Supplies:** Stationeries, printed registers, cleaning detergents, corporate branded collaterals.
- Automated low-stock alerts when inventory drops below safety buffer thresholds.

### 2.3 Store Requisition & Issuance System (SRV / SIV)
- Cross-departmental electronic requisition workflow: Staff submit item requests $\rightarrow$ HOD endorses $\rightarrow$ Stores Officer reviews & issues Store Issue Voucher (SIV).
- Digital handover signature capture on mobile/desktop.

### 2.4 High-Performance Gear & Federation Loan Registry
- Tracks tournament equipment loaned out to national sports federations or national teams with return condition logging.

### 2.5 Physical Stock Count & Accounting Reconciliation
- Bi-annual electronic stock count module enabling mobile tablet spot-checks.
- Automated variance report highlighting book inventory vs physical stock counts, sent to Internal Auditor and Accountant.

---