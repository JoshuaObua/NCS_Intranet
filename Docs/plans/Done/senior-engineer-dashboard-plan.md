# Senior Engineer Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/senior-engineer-dashboard-plan.md`  
> **Role:** Head of Department (HOD) - Senior Engineer  
> **Reports To:** General Secretary (GS)  
> **Oversees:** Engineering Officer Civil, Engineering Officer Electrical  

---

## 1. Role Overview & Objectives

The **Senior Engineer** leads the entire Engineering Department at the National Council of Sports (NCS). The Senior Engineer provides strategic oversight across all NCS sports venues, facility complexes, and administrative infrastructure, ensuring regulatory compliance, high operational readiness for national/international sporting events, fiscal control, and department-wide staff management.

---

## 2. Shared Navigation & Shell Features

The Senior Engineer portal inherits the unified **NCS Intranet Navigation Framework**:

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Senior Engineer Portal   [🔍 Search...] [🔄] [🌙] [🔔 3] [💬 2] [User] |
+-----------------------------------------------------------------------------------+
| [SIDEBAR MENU]  | [MAIN CONTENT DASHBOARD]                                        |
| 1. Dashboard    | +-------------------------------------------------------------+ |
| 2. Work Orders  | | STAT CARDS: Active WOs: 42 | CapEx: 48.5M | Readiness: 94.2% | |
| 3. Assets       | +-------------------------------------------------------------+ |
| 4. Reports      | | [HOD Overview] [CapEx Queue] [My Activities] [Leave]        | |
| 5. My Activities| +-------------------------------------------------------------+ |
| 6. Leave        | | High-Value Requisitions Awaiting Approval (5)               | |
| 7. Messages     | | REQ-041 | Pitch Turf Sprinkler Unit | 12.5M | [Approve]     | |
| 8. Profile      | +-------------------------------------------------------------+ |
| 9. Settings     |                                                                 |
+-----------------------------------------------------------------------------------+
```

### Functional Sidebar & Menu Specs
- **My Activities Menu Item:** Directly routes to `/intranet/my-activities`. Displays a personal timeline of Senior Engineer HOD actions (CapEx decisions, GS report generations, officer leave approvals, login IP history).
- **Navbar Controls:** Light/Dark theme switcher, debounced global search (`Ctrl + K`), instant data refresh button (`🔄`), notification drawer (`🔔`), messages drawer (`💬`).

---

## 3. "My Activities" Personal Audit Log for Senior Engineer

Allows the Senior Engineer to query their account audit log:

```json
// GET /api/v1/intranet/my-activities?category=REQUISITION
{
  "status": "success",
  "data": [
    {
      "id": "act-091",
      "action_type": "CAPEX_REQUISITION_APPROVED",
      "description": "Approved CapEx Requisition REQ-041 (UGX 12,500,000) for Pitch Turf Sprinkler Unit.",
      "target_resource_id": "REQ-041",
      "created_at": "2026-07-29T14:30:00Z"
    },
    {
      "id": "act-088",
      "action_type": "GS_REPORT_GENERATED",
      "description": "Generated Weekly Engineering Brief for General Secretary.",
      "created_at": "2026-07-28T09:15:00Z"
    }
  ]
}
```

---

## 4. Go REST API & Implementation Checklist

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/engineering/senior-engineer/dashboard` | GET | HOD | Dashboard metrics & CapEx queue |
| `/api/v1/intranet/my-activities` | GET | Self Only | Query personal audit logs |

- [ ] **Step 1:** Verify **"My Activities"** sidebar menu link is registered in `SeniorEngineerDashboard.vue`.
- [ ] **Step 2:** Test fetching personal audit logs filtered by date range and action category for Senior Engineer account.
