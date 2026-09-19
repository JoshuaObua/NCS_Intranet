# VPS intranet deployment — 8 September 2026

**Completed:** https://ncsintranet.atenimedia.com

## Deployed change

Deployed the accountant dashboard and independent fixed-asset pages from GitHub commit `90a181580c3cc519893599b906e0ea39e6335d84` into `/opt/ncs-intranet` on `169.58.210.57`.

The VPS had additional changes absent from GitHub. These were preserved by merging, including its same-origin API handling, universal sidebar dropdowns, Command Center improvements and athlete registry. Conflicts caused partly by different line endings were resolved in the layout/router; the VPS's existing Command Center implementation was retained. The resulting **VPS merge commit is `3ceb4d10a3ac73adbd8f5aa9c723575007804f5f`**. This merge was recorded on the VPS; it was not pushed to GitHub.

Only the frontend needed rebuilding. The remote frontend image build passed, followed by:

```bash
cd /opt/ncs-intranet
sudo docker compose up -d --no-deps --no-build frontend
```

No database migration was needed or run. Backend, worker, PostgreSQL, location service, shared gateway and other projects were not restarted. Production `.env` and `docker-compose.yml` were not overwritten; their checksums match the pre-deployment snapshot.

## SSH access

A dedicated Ed25519 key for this machine was appended to `fidi`'s authorized keys and verified with password authentication disabled.

- SSH user/host: `fidi@169.58.210.57`, port 22
- Local key: `/home/fidi/.ssh/ncs_intranet_vps`
- Public-key fingerprint: `SHA256:BmBAoG9+UoZoPqp8PjQ2GTEip74ezxbNaSJVOBCRwxg`
- Local SSH alias: `ssh ncs-intranet-vps`

No password or private key was added to the repository or deployment evidence. Existing authorized keys were preserved.

## Backups and rollback material

VPS backup directory, restricted to root:

`/var/backups/manual/ncsintranet-assets-90a1815-20260908`

It contains the pre-deployment source archive (approximately 139 MB), custom-format PostgreSQL dump (approximately 405 KB), dump object listing, previous commit/image identity, configuration checksums, container snapshots and verification output. The dump was checked by reading its object listing; a restore drill was not performed. The previous deployed source commit was `5ba430af`.

The previous frontend image is retained as `ncsintranet-frontend:rollback-90a1815`. If rollback is required, use this image through a temporary compose override and recreate **only** the frontend with `--no-deps --no-build`. Preserve the production compose file and persistent volumes. No rollback was required during this deployment.

## Verification

- Authenticated using the existing accountant application account against the real HTTPS site.
- Confirmed the register reads all **297 assets** and pagination reaches pages of 100, 100 and 97 records.
- Confirmed New Asset, Revaluation, Verification and Depreciation each open independently.
- Revaluation reload resolves the selected asset through the real API.
- Mobile new-asset page fits the viewport; no browser JavaScript errors occurred.
- Browser checks block asset mutation requests; forms were not submitted to the production ledger.
- Asset row-data fingerprint, count, FB cost, adjusted valuation, accumulated depreciation and transaction-log count are unchanged.
- Only `ncsintranet-frontend-1` changed container ID. **All 23 other observed containers retained their IDs.** No containers were added or removed from the observed set.
- Intranet backend health returned `{"status":"ok"}` directly over its internal network, and PostgreSQL accepts connections.
- Website, portal and intranet public health URLs returned HTTP 200 before and after; bot returned HTTP 302 before and after. The intranet public `/healthz` currently serves SPA HTML, so backend health was additionally checked directly rather than inferred from that HTTP 200.

Asset totals remained **UGX 31,015,914,535 FB cost** and **UGX 32,175,914,535 adjusted cost**, with zero accumulated depreciation and zero asset transaction logs. The source-cost convention and backend control gaps described in the earlier asset audit remain outside this frontend deployment.

[Browser results](browser-results.json) · [Container/data/configuration preservation results](preservation-results.json) · [Read-only production browser script](browser-check.cjs)

## Live screenshots

- [Accountant dashboard](01-accountant-dashboard.png)
- [Asset register](02-register.png)
- [New asset page](03-new-asset-page.png)
- [Revaluation page](04-revaluation-page.png)
- [Verification page](05-verification-page.png)
- [Depreciation page](06-depreciation-page.png)
- [Mobile new-asset page](07-mobile-new-asset.png)

These screenshots use the deployed website and real API data, not mocked responses. The script reads the existing application credential from `Docs/Credentials.csv`; it does not store passwords or tokens in the evidence.
