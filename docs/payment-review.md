# Payment recording and unpaid report review

Reviewed on 24 September 2026.

## Fixed

- **Unpaid report used the wrong records.** Finance collection writes `payments`, but the report previously read `invoices`. It omitted students without invoices and did not subtract recorded payments. The collection screen and unpaid report now share `FeeBalances`, using configured fee items less payments for the same enrolment, session, term, and fee. Gender-specific items follow the collection rules. Applied advances count through their payment entries; pending advances do not.
- **Recorded amount could differ from the submitted amount.** `Collect::recordPayment` previously wrote each selected term's entire balance. It now records exactly the received amount, allocates it in term-ID order, and checks the remaining balance inside a transaction after locking the enrolment. The form accepts partial amounts with up to two decimal places.
- **Partial payments could not be topped up from the collection list.** The record button now remains available while a configured fee has an outstanding balance.
- **Applied advances could be cancelled as ordinary payments.** The collection screen now directs these corrections to Advance Payments, preserving the link between advance credit and its applied payments. Ordinary receipt cancellation still reverses its payment entries and records an audit entry.
- **Reports lacked billing-period detail.** Screen, CSV, and PDF now show session, term, fee, amount due, paid amount, remaining balance, and status, with matching session/term/fee filters. The student count is distinct by student, even with several outstanding fee lines.
- **Receipt text was larger than the advance receipt.** Normal payment receipt body, headings, and total now use the advance receipt's 20px, 34px, 27px, and 28px sizes, including print styles.

## Remaining gaps identified

- **Legacy online billing is separate.** `PaymentController::callback` and `EpaymentController::callback` mark invoices paid and save transactions, but do not post itemised finance payment entries. Their invoices use `section_class_payments` and legacy discount calculations, whereas finance collection uses `section_class_fee_items`. These sources cannot safely be merged by simply subtracting an invoice amount from every fee. Online payments need an explicit allocation/reconciliation process before they can count in the finance unpaid report.
- **Historical fees are not snapshots.** Fee items belong to a class and term, not an academic session. Editing the current fee configuration can change calculated balances for older enrolments. Historical billing needs session-specific fee definitions or stored charges.
- **Older controller endpoints still bypass the collection workflow.** `Finance\PaymentController::add` and `update` only validate required fields, and `add` uses the currently active session. These endpoints need the same amount, class, term, and enrolment/session validation as the collection screen. Their deletion endpoint also uses GET and bypasses the advance cancellation guard. This review's recording and cancellation fixes apply to the Livewire collection screen.
- **Existing incorrect amounts need source receipts.** The application cannot infer the amount actually received from a historical payment entry that was over-recorded. Such entries need receipt-by-receipt review before correction.

## Verification

16 focused tests / 84 assertions passed across `FinanceUnpaidReportTest` and `DeleteAdvancePaymentTest`. Coverage includes partial and multi-term allocation, stale balances, duplicate terms, overpayment, session isolation, gender-specific fees, CSV/PDF exports, receipt totals, advance application, cancellation, and the earlier invoice migration.

Tests use isolated in-memory SQLite databases. Live financial records were not reconciled; the configured MySQL connection was unavailable during the preceding investigation. Concurrent MySQL transactions and physical receipt printing were not exercised.
