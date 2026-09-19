# Engineering Officer Electrical Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/engineering-officer-electrical-dashboard-plan.md`  
> **Role:** Engineering Officer Electrical (Division Head - Electrical Engineering)  
> **Reports To:** Senior Engineer  
> **Oversees:** Assistant Engineer Electrical (and indirectly Electricians, Sound/HVAC Technicians)  

---

## 1. Role Overview & Objectives

The **Engineering Officer Electrical** leads the Electrical Engineering Division at the National Council of Sports (NCS). This role is responsible for power supply across all sports facilities, high-voltage transformer substations, stadium floodlighting towers, emergency backup diesel generators, HVAC, and sound systems.

---

## 2. Shared Navigation & Sidebar Menu Items

Inherits the unified **NCS Intranet Navigation Framework**:

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Eng Officer Electrical    [🔍 Search...] [🔄] [🌙] [🔔 2] [💬 3] [User] |
+-----------------------------------------------------------------------------------+
| [SIDEBAR MENU]  | [MAIN CONTENT DASHBOARD]                                        |
| 1. Dashboard    | +-------------------------------------------------------------+ |
| 2. Power Grid   | | STATS: Mains: ONLINE | Gen 1: 94% Fuel | Floodlights: 4/4 OK | |
| 3. Floodlights  | +-------------------------------------------------------------+ |
| 4. Elec Reports | | [Overview] [Requisitions] [My Activities] [Leave] [Profile] | |
| 5. My Activities| +-------------------------------------------------------------+ |
| 6. Leave        | | Electrical Requisitions Pending Approval (2)                   | |
| 7. Messages     | | REQ-048 | 1000W Floodlight Bulbs | 4.2M | [Approve]            | |
| 8. Profile      | +-------------------------------------------------------------+ |
| 9. Settings     |                                                                 |
+-----------------------------------------------------------------------------------+
```

### Functional Features
- **My Activities Menu Item:** Routes to `/intranet/my-activities`. Displays personal audit logs (electrical requisitions authorized, floodlight checks logged, leave decisions).
- **Navbar Controls:** Theme switcher, global search, refresh, notifications, and messages drawer.

---

## 3. "My Activities" Personal Audit Log for Electrical Officer

```json
// GET /api/v1/intranet/my-activities?category=ELECTRICAL
{
  "status": "success",
  "data": [
    {
      "id": "act-112",
      "action_type": "ELECTRICAL_REQUISITION_AUTHORIZED",
      "description": "Authorized Electrical Requisition REQ-048 (UGX 4,200,000) for Metal Halide Floodlight Bulbs.",
      "created_at": "2026-07-29T10:45:00Z"
    }
  ]
}
```

---

## 4. Go REST API & Checklist

| Endpoint | Method | Scope | Description |
| :--- | :--- | :--- | :--- |
| `/api/v1/engineering/electrical-officer/dashboard` | GET | Elec Officer | Electrical KPIs & queue |
| `/api/v1/intranet/my-activities` | GET | Self Only | Query personal account audit log |

- [ ] **Step 1:** Register **"My Activities"** in `EngineeringOfficerElectricalDashboard.vue` sidebar.
- [ ] **Step 2:** Test personal activity audit query for Electrical Officer account.
