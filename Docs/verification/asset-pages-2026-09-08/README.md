# Independent fixed-asset pages

All four asset-register dialogs have been replaced with routed pages:

| Action | Route | Screenshot |
|---|---|---|
| New asset | `/fixed-assets/new` | [Desktop](01-new-asset-page.png), [mobile](05-new-asset-mobile.png) |
| Revaluation | `/fixed-assets/:id/revalue` | [Page](02-revaluation-page.png) |
| Physical verification | `/fixed-assets/:id/verify` | [Page](03-verification-page.png) |
| Monthly depreciation | `/fixed-assets/depreciation` | [Page](04-depreciation-page.png) |

The routes share one form component for consistent layout and request handling. They have independent URLs, page titles, inline errors and back/cancel navigation. Asset pages load details from the API and work after refresh. Successful saves return to the register and reload its data. Browser validation supports required fields and fractional currency. Submitting disables the form to prevent duplicate requests from the same page.

Ten Chrome checks passed: accountant-dashboard navigation, empty-create validation, API-error retention, decimal create/disabled submission, revaluation refresh/payload, verification prefill/payload, depreciation period, cancel without posting, missing-asset handling and mobile viewport fit. See [results](results.json) and [browser script](check-pages.cjs). All requests are intercepted with fixture responses; no real asset data is written. These checks verify UI/request behavior, not backend accounting correctness.

Run with Vite on port 3001, Chrome installed, and Playwright at `/tmp/ncs-asset-verification`:

```bash
node Docs/verification/asset-pages-2026-09-08/check-pages.cjs
```

The frontend production build also passed. Earlier dialog screenshots in `../asset-register-2026-09-08/` are historical evidence. The existing backend control findings in that report remain outside this page-conversion change.

Integrated the newer remote accountant dashboard before pushing, retained its scoped navigation, and linked its New Fixed Asset tile directly to the new page. Corrected an upstream literal-newline string in `SystemCommandCenterPanel.vue` that prevented the integrated frontend from building.
