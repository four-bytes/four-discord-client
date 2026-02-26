# Changelog

## [2.0.0] - 2026-02-26

### Changed
- Replaced GuzzleHTTP with PSR-18 HTTP client interface
- `WebhookClient` constructor now accepts PSR-18 `ClientInterface`, `RequestFactoryInterface`, `StreamFactoryInterface`
- Added `WebhookClient::create(string $webhookUrl)` static factory using four-http-client
- Added PHPUnit 11 test suite

### Removed
- Hard dependency on `guzzlehttp/guzzle`
