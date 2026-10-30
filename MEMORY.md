# Google API Memory
## Scope
- Package role: Communication (SDKs)
- Purpose: This package operates within the Communication (SDKs) layer of the APIs Hub SaaS hierarchy, providing Google API client functionality.
- Dependency stance: Consumes `anibalealvarezs/api-client-skeleton` and serves the `google-hub-driver` and `apis-hub-facade`.
## Local working rules
- Consult `AGENTS.md` first for package-specific instructions.
- Use this `MEMORY.md` for repository-specific decisions, learnings, and follow-up notes.
- Use `D:\laragon\www\_shared\AGENTS.md` and `D:\laragon\www\_shared\MEMORY.md` for cross-repository protocols and workspace-wide learnings.
- Keep secrets, credentials, tokens, and private endpoints out of this file.
## Current notes
- GA4 compatibility (2026-10-30): AnalyticsDataApi::checkCompatibility POSTs only supported metric/dimension/filter fields to the property endpoint. Driver uses metadata and compatible requested-field coverage before runReport; no source calls in isolated SDK test. Run that test with --bootstrap vendor/autoload.php to avoid configuration/credential bootstrap. Release SDK before the driver which calls the new endpoint.
- Shared Google client for driver and facade integrations.
