# Changelog

## [2.0.1] - 2026-11-14

### Changed
- `four-bytes/four-http-client` `^4.0 || ^5.0` — installable together with packages that need v5; tests pass against v4.2.1 and v5.0.1 (#7)

## [2.0.0] - 2026-02-26

### Changed
- Replaced GuzzleHTTP with PSR-18 HTTP client interface
- `WebhookClient` constructor now accepts PSR-18 `ClientInterface`, `RequestFactoryInterface`, `StreamFactoryInterface`
- Added `WebhookClient::create(string $webhookUrl)` static factory using four-http-client
- Added PHPUnit 11 test suite

### Removed
- Hard dependency on `guzzlehttp/guzzle`
