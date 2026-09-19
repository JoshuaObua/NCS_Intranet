# Assistant Engineer Electrical Dashboard Blueprint & Implementation Plan

> **Target File:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/assistant-engineer-electrical-dashboard-plan.md`  
> **Role:** Assistant Engineer Electrical (Electrical Operations Supervisor)  
> **Reports To:** Engineering Officer Electrical / Senior Engineer  
> **Oversees:** Electricians, Generator Operators, AC/Sound Technicians  
> **Baseline Electrical Assets:** `/home/fidi/Projects/NCS_Intranet/Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx` (`ELECTRICAL MACHINERY` Worksheet, 11 Assets | UGX 107,394,446.00 Valuation + Electrical Equipment Portfolio Scope)  
> **Statutory & Technical Standards:** Uganda National Building Code, IEEE Standards, IPSAS 17 (Property, Plant & Equipment).

---

## 1. Role Overview & Objectives

The **Assistant Engineer Electrical** manages field electrical execution, routine electrical safety checks, 60 KVA & 500 KVA generator servicing logs, cassette & wall-mounted air conditioner maintenance, floodlight operational readiness, and electrical fixed asset tag verification (`ELECTRICAL MACHINERY` category in `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`).

---

## 2. Shared Navigation & Sidebar Menu Items

Inherits the unified **NCS Intranet Navigation Framework**:

```
+-----------------------------------------------------------------------------------+
| [≡] NCS INTRANET | Asst Eng Electrical      [🔍 Search...] [🔄] [🌙] [🔔 3] [💬 2] [User] |
+-----------------------------------------------------------------------------------+
| [SIDEBAR MENU]  | [MAIN CONTENT DASHBOARD]                                        |
| 1. Dashboard    | +-------------------------------------------------------------+ |
| 2. Work Orders  | | STATS: Electrical Assets: 11 | Gen 1 Fuel: 94% | Work Orders: 3 | |
| 3. Generators   | +-------------------------------------------------------------+ |
| 4. Fixed Assets | | [Overview] [Create WO] [My Activities] [Leave] [Profile]     | |
| 5. My Activities| +-------------------------------------------------------------+ |
| 6. Leave        | | Active Electrical Maintenance Tasks (8)                      | |
| 7. Messages     | | WO-0881 | Arena Main Switchboard | Alex Musoke | [Assign]     | |
| 8. Profile      | +-------------------------------------------------------------+ |
| 9. Settings     |                                                                 |
+-----------------------------------------------------------------------------------+
```

### Functional Features
- **Fixed Assets Register Integration (`FixedAssetsView.vue`):** Direct oversight of electrical machinery (60 KVA Generators, Cassette AC units, Wall Mounted ACs, Access Control Systems, Brush Cutters).
- **My Activities Menu Item:** Routes to `/me/activities`. Displays personal activity logs (generator logs recorded, electrical work orders assigned, pre-match floodlight checklists submitted).
- **Navbar Controls:** Theme switcher, global search, refresh, notifications drawer, and messages drawer.

---

## 3. Fixed Asset Register & Personal Audit Log

```json
// GET /api/v1/assets?category=ELECTRICAL MACHINERY
{
  "status": "success",
  "data": {
    "assets": [
      {
        "asset_number": "M1006909",
        "tag_number": "166NCSG0001",
        "asset_description": "60 KVA GENERATOR-166-NCS-G-0001",
        "category_segment3": "ELECTRICAL MACHINERY",
        "fb_cost": 93838196.00,
        "adjusted_cost": 93838196.00,
        "useful_life_years": 5
      }
    ],
    "total": 11
  }
}
```

---

## 4. Go REST API & Checklist

| Endpoint | Method | Scope | Description | Status |
| :--- | :--- | :--- | :--- | :--- |
| `/api/v1/assets` | GET | Electrical | Query 11 electrical machinery assets & generator logs | ✅ Implemented |
| `/api/v1/assets/verify` | POST | Electrical | Electrical spot-check tag verification | ✅ Implemented |
| `/api/v1/engineering/generators/log` | POST, GET | Asst Eng Elec | Generator logging & diesel refills | ✅ Implemented |
| `/api/v1/me/activities` | GET | Self Only | Query personal account audit log | ✅ Implemented |

- [x] Integrate 11 electrical machinery records from `Docs/FIXED ASSET REGISTER ADJUSTMENTS.xlsx`.
- [x] Add **"My Activities"** and **"Fixed Assets"** links to `AssistantEngineerElectricalDashboard.vue` sidebar.
- [x] Test personal activity audit query for Assistant Engineer Electrical account.
