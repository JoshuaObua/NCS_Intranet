# NCS Engineering Department Reengineering Master Plan

> **Target Directory:** `/home/fidi/Projects/NCS_Intranet/Docs/plans/`  
> **System Scope:** National Council of Sports (NCS) Intranet - Engineering Division Module  
> **Tech Stack:** Go (Backend REST API), PostgreSQL (Database), Vue 3 + TailwindCSS + Pinia (Frontend), Docker / Nginx  

---

## 1. Executive Summary & Vision

The National Council of Sports (NCS) manages sports infrastructure, stadiums (such as Lugogo Sports Complex), indoor arenas, administrative buildings, and grounds across Uganda. The Engineering Department is responsible for maintaining all physical, structural, civil, electrical, plumbing, and power assets in prime operating condition.

This Master Plan defines the digital transformation and workflow reengineering of the Engineering Department within the **NCS Intranet**. It establishes an end-to-end digital lifecycle for asset management, work order dispatch, material requisitions, site inspections, emergency triage, shared intranet modules (Notifications, Messaging, Leave Management, Profile, Settings, My Activities Personal Audit Log, **PPDA Procurement Form 5**), functional responsive navigation (Navbar, Sidebar, Theme Toggle), and a **Cascading Hierarchical Reporting System** connecting up to the **General Secretary Executive Master Dashboard**.

---

## 2. Organizational Structure & Reporting Lines

```
                     +---------------------------------------+
                     |    General Secretary (NCS GS)         |
                     |  (Executive Approval & OVERSIGHT)     |
                     +-------------------+-------------------+
                                         |
                     +-------------------+-------------------+
                     |      Senior Engineer (HOD)            |
                     | (Overall Department Administration)   |
                     +-------------------+-------------------+
                                         |
          +------------------------------+------------------------------+
          |                                                             |
+---------+-------------------------+                         +---------+-------------------------+
| Engineering Officer Civil         |                         | Engineering Officer Electrical    |
| (Civil Assets & Infrastructure)   |                         | (Power, Lighting & HVAC Assets)  |
+---------+-------------------------+                         +---------+-------------------------+
          |                                                             |
+---------+-------------------------+                         +---------+-------------------------+
| Assistant Engineer Civil          |                         | Assistant Engineer Electrical     |
| (Civil Operations & Site Lead)    |                         | (Electrical Operations Lead)    |
+---------+-------------------------+                         +---------------------------------+
          |
+---------+-------------------------+
| Plumbers / Trade Technicians      |
| (Field Execution & Maintenance)   |
+-----------------------------------+
```

---

## 3. Shared Intranet Modules & Navigation Menu Items

All role dashboards across all departments include the following standard menu items:

1. **Dashboard:** Role-specific main overview panel.
2. **Work Orders / Assets:** Role-specific operational management.
3. **Procurement Form 5 (`/intranet/procurement/form-5`):** Statutory PPDA Form 5 request submittal for goods, works, and services.
4. **Reports:** Cascading hierarchical reporting (superiority-scoped access).
5. **My Activities (`/intranet/my-activities`):** Account-isolated personal audit log.
6. **Leave:** Leave application & supervisor approvals.
7. **Messages:** Direct 1-on-1 & department messaging center.
8. **Profile:** Personal profile & qualification management.
9. **Settings:** Password change, theme preference, 2FA.

---

## 4. Master Document Roadmap in `/home/fidi/Projects/NCS_Intranet/Docs/plans/`

1. `master-engineering-department-plan.md` (This document)
2. `general-secretary-dashboard-plan.md` (Executive Master Control & Statutory Approval Blueprint)
3. `all-departments-framework-plan.md` (IT, Finance, HR, Sports Admin, Procurement Blueprint)
4. `procurement-form-5-plan.md` (PPDA Form 5 Submission & Approval Blueprint)
5. `senior-engineer-dashboard-plan.md`
6. `engineering-officer-civil-dashboard-plan.md`
7. `engineering-officer-electrical-dashboard-plan.md`
8. `assistant-engineer-civil-dashboard-plan.md`
9. `assistant-engineer-electrical-dashboard-plan.md`
10. `plumber-dashboard-plan.md`
11. `seamless-dashboard-integration-plan.md`
