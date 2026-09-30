<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/bhclogo.jpg">
    <title>Stock-Out Log - Velasquez Health Center</title>

    <style>
        /* =========================================================
           GLOBAL
        ========================================================= */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #F8FAFC;
            color: #1F2937;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 24px 28px 40px;
            box-sizing: border-box;
        }

        /* =========================================================
           HEADER
        ========================================================= */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .welcome-text {
            font-size: 13px;
            color: #64748B;
            margin-bottom: 5px;
        }

        .welcome-name {
            font-weight: 700;
            color: #111827;
            font-size: 18px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .role {
            background: {{ $roleColor ?? '#9333EA' }};
            color: white;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 8px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .header-right {
            text-align: right;
            font-size: 12px;
            color: #64748B;
            line-height: 1.6;
        }

        .header-right strong {
            color: #334155;
            font-size: 12.5px;
        }

        .header-divider {
            width: 100%;
            height: 1px;
            background: #E2E8F0;
            margin: 17px 0 26px;
        }

        /* =========================================================
           PAGE HEADER
        ========================================================= */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 20px;
        }

        .page-title-wrapper {
            min-width: 0;
        }

        .page-eyebrow {
            font-size: 11px;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-title {
            margin: 0;
            color: #0F172A;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 700;
        }

        .page-description {
            margin: 6px 0 0;
            color: #64748B;
            font-size: 12.5px;
            line-height: 1.5;
        }

        /* =========================================================
           KPI CARDS
        ========================================================= */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            position: relative;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 17px 18px;
            min-height: 108px;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, .06);
        }

        .stat-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }

        .stat-card.red::before {
            background: #DC2626;
        }

        .stat-card.blue::before {
            background: #2563EB;
        }

        .stat-card.yellow::before {
            background: #D97706;
        }

        .stat-card.grey::before {
            background: #64748B;
        }

        .stat-title {
            font-size: 10.5px;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: .055em;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 26px;
            line-height: 1;
            font-weight: 700;
            color: #111827;
        }

        .stat-unit {
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
        }

        .stat-description {
            margin-top: 8px;
            color: #94A3B8;
            font-size: 10.5px;
        }

        .stat-card.red {
            background: #FFF7F7;
            border-color: #FECACA;
        }

        .stat-card.red .stat-title {
            color: #B91C1C;
        }

        .stat-card.red .stat-number {
            color: #991B1B;
        }

        .stat-card.yellow {
            background: #FFFBEB;
            border-color: #FDE68A;
        }

        .stat-card.yellow .stat-title {
            color: #B45309;
        }

        .stat-card.yellow .stat-number {
            color: #92400E;
        }

        /* =========================================================
           TABS
        ========================================================= */
        .so-tabs-wrapper {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px 12px 0 0;
            padding: 0 18px;
        }

        .so-tabs {
            display: flex;
            gap: 4px;
        }

        .so-tab {
            position: relative;
            background: none;
            border: none;
            padding: 14px 14px 13px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .so-tab::after {
            content: "";
            position: absolute;
            left: 8px;
            right: 8px;
            bottom: -1px;
            height: 2px;
            background: transparent;
            border-radius: 3px 3px 0 0;
        }

        .so-tab:hover {
            color: #334155;
        }

        .so-tab.active {
            color: #2563EB;
        }

        .so-tab.active::after {
            background: #2563EB;
        }

        .so-tab-count {
            min-width: 22px;
            height: 20px;
            padding: 0 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #F1F5F9;
            color: #475569;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .so-tab.active .so-tab-count {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        /* =========================================================
           TABLE CARD
        ========================================================= */
        .table-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-top: none;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .toolbar-section {
            min-height: 58px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-bottom: 1px solid #F1F5F9;
        }

        .toolbar-title {
            font-size: 14px;
            font-weight: 700;
            color: #1E293B;
        }

        .toolbar-subtitle {
            margin-top: 3px;
            color: #94A3B8;
            font-size: 11px;
        }

        .table-count {
            color: #64748B;
            font-size: 11px;
            font-weight: 600;
        }

        .overflow-x-auto {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            text-align: left;
        }

        thead th {
            background: #F8FAFC;
            color: #64748B;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .055em;
            padding: 11px 16px;
            border-bottom: 1px solid #E2E8F0;
            text-transform: uppercase;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #F1F5F9;
            transition: background .12s ease;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #F8FAFC;
        }

        tbody td {
            padding: 13px 16px;
            color: #475569;
            vertical-align: middle;
            white-space: nowrap;
        }

        .medicine-name {
            color: #1E293B;
            font-weight: 700;
        }

        .stock-value {
            font-weight: 600;
            color: #334155;
        }

        .affected-value {
            font-weight: 700;
            color: #334155;
        }

        .date-main {
            color: #334155;
            font-weight: 600;
        }

        .date-sub {
            color: #94A3B8;
            font-size: 10.5px;
            margin-top: 2px;
        }

        .text-action {
            color: #2563EB;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }

        .text-action:hover {
            text-decoration: underline;
        }

        .text-empty {
            padding: 46px 20px !important;
            text-align: center;
            color: #94A3B8;
            white-space: normal !important;
        }

        .empty-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94A3B8;
            font-size: 18px;
        }

        /* =========================================================
           STATUS BADGES
        ========================================================= */
        .so-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 10.5px;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 999px;
        }

        .so-badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .so-badge-active {
            background: #FEF2F2;
            color: #B91C1C;
        }

        .so-badge-active::before {
            background: #DC2626;
        }

        .so-badge-resolved {
            background: #F0FDF4;
            color: #15803D;
        }

        .so-badge-resolved::before {
            background: #16A34A;
        }

        /* =========================================================
           EXPIRED BATCHES
        ========================================================= */
        .expired-section {
            margin-top: 24px;
        }

        .expired-card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            overflow: hidden;
        }

        .expired-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 16px 18px;
            border-bottom: 1px solid #F1F5F9;
        }

        .expired-title-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 11px;
        }

        .expired-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #FEF2F2;
            color: #DC2626;
            font-size: 15px;
            font-weight: 700;
        }

        .expired-title {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #1E293B;
        }

        .expired-subtitle {
            margin: 3px 0 0;
            font-size: 11px;
            color: #94A3B8;
        }

        .expired-count {
            background: #FEF2F2;
            color: #B91C1C;
            border: 1px solid #FECACA;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
        }

        #expiredBatchesTable {
            width: 100%;
            border-collapse: collapse;
        }

        #expiredBatchesTable th {
            text-align: left;
            font-size: 10px;
            color: #64748B;
            padding: 11px 16px;
            background: #F8FAFC;
            border-bottom: 1px solid #E2E8F0;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        #expiredBatchesTable td {
            padding: 12px 16px;
            border-bottom: 1px solid #F1F5F9;
            font-size: 12px;
            color: #475569;
        }

        #expiredBatchesTable tr:last-child td {
            border-bottom: none;
        }

        .exp-medicine {
            color: #1E293B;
            font-weight: 700;
        }

        .exp-batch-number {
            font-family: monospace;
            font-size: 11px;
            color: #475569;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            padding: 3px 6px;
            border-radius: 5px;
        }

        .exp-batch-status {
            display: inline-flex;
            align-items: center;
            background: #FEF2F2;
            color: #991B1B;
            font-size: 10.5px;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 999px;
        }

        .exp-batch-view-btn {
            color: #2563EB;
            font-size: 11.5px;
            font-weight: 600;
            text-decoration: none;
        }

        .exp-batch-view-btn:hover {
            text-decoration: underline;
        }

        .exp-batches-empty {
            padding: 35px !important;
            text-align: center;
            color: #94A3B8;
        }

        /* =========================================================
           ALERTS
        ========================================================= */
        .alert {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 8px;
            font-size: 12.5px;
        }

        .alert-success {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            color: #166534;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
        }

        .alert-error ul {
            margin: 7px 0 0;
            padding-left: 18px;
        }

        /* =========================================================
           STOCK-OUT DETAILS MODAL
        ========================================================= */
        .so-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .48);
            backdrop-filter: blur(2px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .so-modal-box {
            background: #FFFFFF;
            width: 100%;
            max-width: 760px;
            max-height: 86vh;
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(15, 23, 42, .2);
            animation: modalIn .16s ease;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(8px) scale(.99);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .so-modal-header {
            padding: 17px 20px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .so-modal-header h3 {
            margin: 0;
            font-size: 15px;
            color: #111827;
        }

        .so-modal-close {
            width: 32px;
            height: 32px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 7px;
            font-size: 20px;
            cursor: pointer;
            color: #64748B;
            line-height: 1;
        }

        .so-modal-close:hover {
            background: #F1F5F9;
            color: #111827;
        }

        .so-modal-body {
            padding: 20px;
            overflow-y: auto;
        }

        .so-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        .so-info-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 11px 13px;
        }

        .so-info-item .k {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94A3B8;
            letter-spacing: .05em;
        }

        .so-info-item .v {
            font-size: 12.5px;
            font-weight: 600;
            color: #1E293B;
            margin-top: 4px;
        }

        .modal-section-title {
            font-size: 13px;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 9px;
        }

        .so-impact-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        .so-impact-table th {
            text-align: left;
            background: #F8FAFC;
            color: #64748B;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 9px 10px;
            border-bottom: 1px solid #E2E8F0;
        }

        .so-impact-table td {
            padding: 10px;
            border-bottom: 1px solid #F1F5F9;
            color: #475569;
        }

        .so-impact-table tr:last-child td {
            border-bottom: none;
        }

        .so-status-pill {
            display: inline-flex;
            font-size: 9.5px;
            font-weight: 700;
            padding: 4px 7px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .so-status-pill.partial {
            background: #FFFBEB;
            color: #92400E;
        }

        .so-status-pill.none {
            background: #FEF2F2;
            color: #991B1B;
        }

        /* =========================================================
           STOCK CARD MODAL
        ========================================================= */
        .stock-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .5);
            backdrop-filter: blur(2px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1100;
            opacity: 0;
            transition: opacity .2s ease;
        }

        .stock-modal-overlay.show {
            opacity: 1;
        }

        .stock-card-modal {
            background: #FFFFFF;
            width: 100%;
            max-width: 900px;
            max-height: calc(100vh - 40px);
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(15, 23, 42, .2);
        }

        .stock-card-modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .stock-card-title {
            font-size: 17px;
            font-weight: 700;
            color: #111827;
        }

        .stock-card-subtitle {
            margin-top: 3px;
            font-size: 11.5px;
            color: #64748B;
        }

        .stock-close-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            border-radius: 7px;
            cursor: pointer;
            font-size: 20px;
            color: #64748B;
        }

        .stock-close-btn:hover {
            color: #111827;
            background: #F1F5F9;
        }

        .stock-card-content {
            padding: 20px 22px;
        }

        .stock-card-heading {
            text-align: center;
            margin-bottom: 15px;
        }

        .gov-sub-title {
            font-size: 10px;
            color: #64748B;
            letter-spacing: .08em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .gov-main-title {
            font-size: 14px;
            color: #111827;
            font-weight: 700;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .stock-card-metadata {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 22px;
            padding: 13px 15px;
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 9px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 0;
            border-bottom: 1px solid #E2E8F0;
            font-size: 11.5px;
        }

        .meta-row:nth-last-child(-n+2) {
            border-bottom: none;
        }

        .meta-label {
            color: #64748B;
            font-weight: 600;
        }

        .meta-value {
            margin-left: auto;
            color: #111827;
            font-weight: 600;
            text-align: right;
        }

        .stock-card-table-wrap {
            margin-top: 16px;
            overflow-x: auto;
            border: 1px solid #E2E8F0;
            border-radius: 9px;
        }

        .stock-card-table {
            width: 100%;
            min-width: 560px;
            border-collapse: collapse;
        }

        .stock-card-table th,
        .stock-card-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #E2E8F0;
        }

        .stock-card-table th {
            background: #F8FAFC;
            color: #475569;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            text-align: left;
        }

        .stock-card-table td {
            color: #334155;
            font-size: 12px;
        }

        .stock-card-table tbody tr:last-child td {
            border-bottom: none;
        }

        .received-value {
            color: #16A34A;
            font-weight: 700;
        }

        .issued-value {
            color: #DC2626;
            font-weight: 700;
        }

        .balance-value {
            color: #2563EB;
            font-weight: 700;
        }

        .stock-card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #E2E8F0;
        }

        .print-btn,
        .btn-close {
            border-radius: 7px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .print-btn {
            border: 1px solid #CBD5E1;
            background: #FFFFFF;
            color: #334155;
        }

        .print-btn:hover {
            background: #F8FAFC;
        }

        .btn-close {
            background: #E5E7EB;
            color: #374151;
            border: none;
        }

        .btn-close:hover {
            background: #D1D5DB;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 1200px) {
            .main {
                padding: 22px;
            }

            .stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 992px) {
            .main {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .header {
                flex-direction: column;
            }

            .header-right {
                text-align: left;
            }

            .page-header {
                align-items: flex-start;
            }

            .stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .main {
                padding: 16px;
            }

            .page-title {
                font-size: 21px;
            }

            .page-header {
                margin-bottom: 16px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .stat-card {
                min-height: 95px;
            }

            .so-tabs-wrapper {
                padding: 0 8px;
                overflow-x: auto;
            }

            .so-tab {
                white-space: nowrap;
                padding-left: 11px;
                padding-right: 11px;
            }

            .toolbar-section {
                align-items: flex-start;
            }

            .so-info-grid {
                grid-template-columns: 1fr;
            }

            .stock-card-metadata {
                grid-template-columns: 1fr;
            }

            .meta-row:nth-last-child(-n+2) {
                border-bottom: 1px solid #E2E8F0;
            }

            .meta-row:last-child {
                border-bottom: none;
            }

            .so-modal-backdrop,
            .stock-modal-overlay {
                padding: 10px;
            }

            .so-modal-box,
            .stock-card-modal {
                max-height: 92vh;
            }

            .expired-header {
                align-items: flex-start;
            }
        }

        /* =========================================================
           PRINT
        ========================================================= */
        @media print {
            body {
                background: #FFFFFF;
            }

            .main {
                margin: 0;
                width: 100%;
            }

            .stock-modal-overlay {
                position: static;
                display: block !important;
                opacity: 1 !important;
                background: none;
                padding: 0;
            }

            .stock-card-modal {
                max-width: none;
                max-height: none;
                box-shadow: none;
                border: none;
            }

            .stock-card-actions,
            .stock-close-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <x-sidebar />

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- HEADER -->
        <header class="header">
            <div>
                <div class="welcome-text">Welcome back,</div>

                <div class="welcome-name">
                    {{ $userName ?? 'User' }}

                    <span class="role">
                        {{ strtoupper($role ?? 'ADMIN') }}
                    </span>
                </div>
            </div>

            <div class="header-right">
                <strong>Velasquez Health Center</strong><br>

                @php
                    date_default_timezone_set('Asia/Manila');
                @endphp

                {{ date('F d, Y | h:i A') }}
            </div>
        </header>

        <div class="header-divider"></div>

        <!-- SUCCESS MESSAGE -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Could not save. Please fix the following:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- PAGE HEADER -->
        <div class="page-header">
            <div class="page-title-wrapper">
                <div class="page-eyebrow">Inventory Monitoring</div>

                <h1 class="page-title">
                    Stock-Out Log
                </h1>

                <p class="page-description">
                    Monitor medicine stock-outs, affected patients, and resolved inventory incidents.
                </p>
            </div>
        </div>

        <!-- =====================================================
             KPI CARDS
        ====================================================== -->
        <section class="stats-row">

            <div class="stat-card red">
                <div class="stat-title">
                    Currently Out of Stock
                </div>

                <div class="stat-number">
                    {{ number_format($activeCount ?? 0) }}
                </div>

                <div class="stat-description">
                    Medicines currently unavailable
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-title">
                    Total Stock-Out Incidents
                </div>

                <div class="stat-number">
                    {{ number_format($totalStockOut ?? 0) }}
                </div>

                <div class="stat-description">
                    Recorded stock-out events
                </div>
            </div>

            <div class="stat-card yellow">
                <div class="stat-title">
                    Total Patients Affected
                </div>

                <div class="stat-number">
                    {{ number_format($patientsAffected ?? 0) }}
                </div>

                <div class="stat-description">
                    Patients with affected dispensing
                </div>
            </div>

            <div class="stat-card grey">
                <div class="stat-title">
                    Average Stock-Out Duration
                </div>

                <div class="stat-number">
                    {{ number_format($avgDuration ?? 0, 1) }}
                    <span class="stat-unit">days</span>
                </div>

                <div class="stat-description">
                    Average time until resolution
                </div>
            </div>

        </section>

        <!-- =====================================================
             TABS
        ====================================================== -->
        <div class="so-tabs-wrapper">

            <div class="so-tabs">

                <button
                    type="button"
                    class="so-tab active"
                    onclick="soSwitchTab('active')"
                    id="soTabActiveBtn"
                >
                    Active Stock-Outs

                    <span class="so-tab-count">
                        {{ $activeStockOuts->count() ?? 0 }}
                    </span>
                </button>

                <button
                    type="button"
                    class="so-tab"
                    onclick="soSwitchTab('history')"
                    id="soTabHistoryBtn"
                >
                    Stock-Out History

                    <span class="so-tab-count">
                        {{ $stockOutHistory->count() ?? 0 }}
                    </span>
                </button>

            </div>

        </div>

        <!-- =====================================================
             ACTIVE STOCK-OUTS
        ====================================================== -->
        <section
            class="table-card"
            id="soActivePanel"
        >

            <div class="toolbar-section">

                <div>
                    <div class="toolbar-title">
                        Active Stock-Outs
                    </div>

                    <div class="toolbar-subtitle">
                        Medicines that are currently unavailable
                    </div>
                </div>

                <div class="table-count">
                    {{ $activeStockOuts->count() ?? 0 }} active
                </div>

            </div>

            <div class="overflow-x-auto">

                <table>

                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Usable Stock</th>
                            <th>Stock-Out Date</th>
                            <th>Duration</th>
                            <th>Patients Affected</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($activeStockOuts ?? [] as $item)

                            <tr>

                                <td>
                                    <div class="medicine-name">
                                        {{ $item->medicine_name }}
                                    </div>
                                </td>

                                <td>
                                    <span class="stock-value">
                                        {{ $item->usable_stock }}
                                    </span>
                                </td>

                                <td>
                                    <div class="date-main">
                                        {{ $item->stockout_date->format('M d, Y') }}
                                    </div>

                                    <div class="date-sub">
                                        {{ $item->stockout_date->format('h:i A') }}
                                    </div>
                                </td>

                                <td>
                                    {{ $item->days_out }}
                                    {{ $item->days_out == 1 ? 'day' : 'days' }}
                                </td>

                                <td>
                                    <span class="affected-value">
                                        {{ $item->affected_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="so-badge so-badge-active">
                                        Active
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="#"
                                        class="text-action"
                                        onclick="soViewDetails({{ $item->id }}); return false;"
                                    >
                                        View Details
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-empty">

                                    <div class="empty-icon">
                                        ✓
                                    </div>

                                    <strong>No active stock-outs</strong>

                                    <div style="margin-top:5px;">
                                        All currently tracked medicines have usable stock.
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

        <!-- =====================================================
             STOCK-OUT HISTORY
        ====================================================== -->
        <section
            class="table-card"
            id="soHistoryPanel"
            style="display:none;"
        >

            <div class="toolbar-section">

                <div>
                    <div class="toolbar-title">
                        Stock-Out History
                    </div>

                    <div class="toolbar-subtitle">
                        Previously recorded and resolved stock-out incidents
                    </div>
                </div>

                <div class="table-count">
                    {{ $stockOutHistory->count() ?? 0 }} records
                </div>

            </div>

            <div class="overflow-x-auto">

                <table>

                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Stock-Out Date</th>
                            <th>Resolved Date</th>
                            <th>Duration</th>
                            <th>Patients Affected</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stockOutHistory ?? [] as $item)

                            <tr>

                                <td>
                                    <div class="medicine-name">
                                        {{ $item->medicine_name }}
                                    </div>
                                </td>

                                <td>
                                    <div class="date-main">
                                        {{ $item->stockout_date->format('M d, Y') }}
                                    </div>

                                    <div class="date-sub">
                                        {{ $item->stockout_date->format('h:i A') }}
                                    </div>
                                </td>

                                <td>

                                    @if($item->resolved_date)

                                        <div class="date-main">
                                            {{ $item->resolved_date->format('M d, Y') }}
                                        </div>

                                        <div class="date-sub">
                                            {{ $item->resolved_date->format('h:i A') }}
                                        </div>

                                    @else
                                        —
                                    @endif

                                </td>

                                <td>
                                    {{ $item->duration_days ?? '-' }}

                                    @if($item->duration_days !== null)
                                        {{ $item->duration_days == 1 ? 'day' : 'days' }}
                                    @endif
                                </td>

                                <td>
                                    <span class="affected-value">
                                        {{ $item->affected_count }}
                                    </span>
                                </td>

                                <td>
                                    <span class="so-badge so-badge-resolved">
                                        Resolved
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="#"
                                        class="text-action"
                                        onclick="soViewDetails({{ $item->id }}); return false;"
                                    >
                                        View Details
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-empty">

                                    <div class="empty-icon">
                                        —
                                    </div>

                                    <strong>No stock-out history</strong>

                                    <div style="margin-top:5px;">
                                        No resolved stock-out incidents have been recorded yet.
                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

        <!-- =====================================================
             EXPIRED BATCHES
        ====================================================== -->
        <section class="expired-section">

            <div class="expired-card">

                <div class="expired-header">

                    <div class="expired-title-wrapper">

                        <div class="expired-icon">
                            !
                        </div>

                        <div>
                            <h2 class="expired-title">
                                Expired Batches
                            </h2>

                            <p class="expired-subtitle">
                                Medicine batches that should no longer be dispensed
                            </p>
                        </div>

                    </div>

                    <span
                        class="expired-count"
                        id="expiredBatchCount"
                    >
                        0 expired
                    </span>

                </div>

                <div class="overflow-x-auto">

                    <table id="expiredBatchesTable">

                        <thead>
                            <tr>
                                <th>Medicine</th>
                                <th>Batch Number</th>
                                <th>Expiration Date</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="expiredBatchesBody">

                            <tr>
                                <td colspan="6" class="exp-batches-empty">
                                    Loading expired batches...
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>

    </main>

</div>

<!-- =========================================================
     STOCK-OUT DETAILS MODAL
========================================================= -->

<div
    id="soDetailsModal"
    class="so-modal-backdrop"
    style="display:none;"
>

    <div class="so-modal-box">

        <div class="so-modal-header">

            <h3 id="soDetailsTitle">
                Stock-Out Details
            </h3>

            <button
                type="button"
                class="so-modal-close"
                onclick="soCloseDetails()"
                aria-label="Close"
            >
                &times;
            </button>

        </div>

        <div
            class="so-modal-body"
            id="soDetailsBody"
        >
            <div style="text-align:center; padding:40px; color:#94A3B8;">
                Loading...
            </div>
        </div>

    </div>

</div>

<!-- =========================================================
     STOCK CARD MODAL
========================================================= -->

<div
    class="stock-modal-overlay"
    id="stockCardModal"
>

    <div class="stock-card-modal">

        <div class="stock-card-modal-header">

            <div>
                <div
                    class="stock-card-title"
                    id="sc-title"
                >
                    Stock Card —
                </div>

                <div
                    class="stock-card-subtitle"
                    id="sc-batch"
                >
                    Batch: —
                </div>
            </div>

            <button
                type="button"
                class="stock-close-btn"
                onclick="closeStockCard()"
                aria-label="Close"
            >
                &times;
            </button>

        </div>

        <div class="stock-card-content">

            <div class="stock-card-heading">

                <div class="gov-sub-title">
                    Republic of the Philippines
                </div>

                <div class="gov-main-title">
                    Barangay Health Center — Stock Card
                </div>

            </div>

            <div class="stock-card-metadata">

                <div class="meta-row">
                    <span class="meta-label">
                        Item Description
                    </span>

                    <span
                        class="meta-value"
                        id="sc-item"
                    >
                        —
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">
                        Expiration Date
                    </span>

                    <span
                        class="meta-value"
                        id="sc-expiry"
                    >
                        —
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">
                        Batch Number
                    </span>

                    <span
                        class="meta-value"
                        id="sc-batchnum"
                    >
                        —
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">
                        Entity Name
                    </span>

                    <span class="meta-value">
                        Velasquez Health Center
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">
                        Mode
                    </span>

                    <span
                        class="meta-value mode-val"
                    >
                        —
                    </span>
                </div>

            </div>

            <div class="stock-card-table-wrap">

                <table class="stock-card-table">

                    <thead>

                        <tr>
                            <th>DATE</th>
                            <th style="color:#16A34A;">RECEIVED</th>
                            <th style="color:#DC2626;">ISSUED</th>
                            <th style="color:#2563EB;">BALANCE</th>
                            <th>REMARKS</th>
                        </tr>

                    </thead>

                    <tbody id="sc-table-body"></tbody>

                </table>

            </div>

            <div class="stock-card-actions">

                <button
                    type="button"
                    class="print-btn"
                    onclick="window.print()"
                >
                    Print
                </button>

                <button
                    type="button"
                    class="btn-close"
                    onclick="closeStockCard()"
                >
                    Close
                </button>

            </div>

        </div>

    </div>

</div>

<script>
    /* =========================================================
       TAB SWITCHING
    ========================================================= */

    function soSwitchTab(tab) {

        const activePanel = document.getElementById('soActivePanel');
        const historyPanel = document.getElementById('soHistoryPanel');

        const activeBtn = document.getElementById('soTabActiveBtn');
        const historyBtn = document.getElementById('soTabHistoryBtn');

        if (tab === 'active') {

            activePanel.style.display = 'block';
            historyPanel.style.display = 'none';

            activeBtn.classList.add('active');
            historyBtn.classList.remove('active');

        } else {

            activePanel.style.display = 'none';
            historyPanel.style.display = 'block';

            activeBtn.classList.remove('active');
            historyBtn.classList.add('active');
        }
    }


    /* =========================================================
       STOCK-OUT DETAILS
    ========================================================= */

    function soViewDetails(id) {

        const modal = document.getElementById('soDetailsModal');
        const body = document.getElementById('soDetailsBody');

        modal.style.display = 'flex';

        body.innerHTML = `
            <div style="text-align:center; padding:40px; color:#94A3B8;">
                Loading...
            </div>
        `;

        fetch(`/stockout/${id}/details`)
            .then(res => {

                if (!res.ok) {
                    throw new Error('Unable to load details.');
                }

                return res.json();
            })
            .then(data => {

                document.getElementById('soDetailsTitle').textContent =
                    `${data.medicine_name} - Stock-Out Details`;

                let rows = '';

                if (
                    data.affected_patients &&
                    data.affected_patients.length
                ) {

                    data.affected_patients.forEach(p => {

                        const statusClass =
                            p.status === 'not_dispensed_stockout'
                                ? 'none'
                                : 'partial';

                        const statusLabel =
                            p.status === 'not_dispensed_stockout'
                                ? 'Not Dispensed'
                                : 'Partially Dispensed';

                        rows += `
                            <tr>
                                <td>${p.patient_name ?? '-'}</td>
                                <td>${p.prescribed ?? '-'}</td>
                                <td>${p.dispensed ?? 0}</td>
                                <td>${p.unfulfilled ?? 0}</td>
                                <td>
                                    <span class="so-status-pill ${statusClass}">
                                        ${statusLabel}
                                    </span>
                                </td>
                                <td>${p.date ?? '-'}</td>
                            </tr>
                        `;
                    });

                } else {

                    rows = `
                        <tr>
                            <td
                                colspan="6"
                                class="text-empty"
                            >
                                No affected patients recorded for this stock-out.
                            </td>
                        </tr>
                    `;
                }

                body.innerHTML = `

                    <div class="so-info-grid">

                        <div class="so-info-item">
                            <div class="k">Status</div>
                            <div class="v">
                                ${
                                    data.status === 'active'
                                        ? 'Active'
                                        : 'Resolved'
                                }
                            </div>
                        </div>

                        <div class="so-info-item">
                            <div class="k">Duration</div>
                            <div class="v">
                                ${data.duration_days}
                                ${
                                    data.duration_days == 1
                                        ? 'day'
                                        : 'days'
                                }
                            </div>
                        </div>

                        <div class="so-info-item">
                            <div class="k">Stock-Out Started</div>
                            <div class="v">
                                ${data.stockout_date}
                            </div>
                        </div>

                        <div class="so-info-item">
                            <div class="k">Resolved</div>
                            <div class="v">
                                ${
                                    data.resolved_date ??
                                    'Not yet resolved'
                                }
                            </div>
                        </div>

                    </div>

                    <div class="modal-section-title">
                        Patient Impact
                    </div>

                    <div class="overflow-x-auto">

                        <table class="so-impact-table">

                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Prescribed</th>
                                    <th>Dispensed</th>
                                    <th>Unfulfilled</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                ${rows}
                            </tbody>

                        </table>

                    </div>
                `;
            })
            .catch(() => {

                body.innerHTML = `
                    <div style="
                        text-align:center;
                        padding:40px;
                        color:#DC2626;
                    ">
                        Unable to load stock-out details.
                    </div>
                `;
            });
    }


    function soCloseDetails() {

        document.getElementById(
            'soDetailsModal'
        ).style.display = 'none';
    }


    /* Close details modal by clicking outside */
    document.getElementById('soDetailsModal')
        .addEventListener('click', function(e) {

            if (e.target === this) {
                soCloseDetails();
            }
        });


    /* =========================================================
       EXPIRED BATCHES
    ========================================================= */

    function loadExpiredBatches() {

        fetch('/stockout/expired-batches')

            .then(res => {

                if (!res.ok) {
                    throw new Error('Unable to load expired batches.');
                }

                return res.json();
            })

            .then(data => {

                const body =
                    document.getElementById('expiredBatchesBody');

                const count =
                    document.getElementById('expiredBatchCount');

                const batches = data.batches || [];

                count.textContent =
                    `${batches.length} ${
                        batches.length === 1
                            ? 'expired'
                            : 'expired'
                    }`;

                if (batches.length === 0) {

                    body.innerHTML = `
                        <tr>
                            <td
                                colspan="6"
                                class="exp-batches-empty"
                            >
                                No expired batches recorded.
                            </td>
                        </tr>
                    `;

                    return;
                }

                window.currentExpiredBatches = batches;

                body.innerHTML = batches.map((b, idx) => `

                    <tr>

                        <td>
                            <span class="exp-medicine">
                                ${b.medicine_name}
                            </span>
                        </td>

                        <td>
                            <span class="exp-batch-number">
                                ${b.batch_number}
                            </span>
                        </td>

                        <td>
                            ${b.expiry_date}
                        </td>

                        <td>
                            ${b.quantity}
                        </td>

                        <td>
                            <span class="exp-batch-status">
                                Expired
                            </span>
                        </td>

                        <td>

                            <a
                                href="javascript:void(0)"
                                class="exp-batch-view-btn"
                                data-index="${idx}"
                                onclick="handleExpiredViewDetails(this)"
                            >
                                View Stock Card
                            </a>

                        </td>

                    </tr>

                `).join('');
            })

            .catch(() => {

                document.getElementById(
                    'expiredBatchesBody'
                ).innerHTML = `

                    <tr>
                        <td
                            colspan="6"
                            class="exp-batches-empty"
                            style="color:#DC2626;"
                        >
                            Unable to load expired batches.
                        </td>
                    </tr>

                `;
            });
    }


    /* =========================================================
       STOCK CARD
    ========================================================= */

    function viewBatch(medicineName, batchString) {

        let batch;

        try {
            batch = JSON.parse(batchString);
        } catch (e) {
            batch = batchString;
        }

        const modal =
            document.getElementById('stockCardModal');

        if (!modal) {
            return;
        }

        modal.style.display = 'flex';

        setTimeout(() => {
            modal.classList.add('show');
        }, 10);

        document.getElementById('sc-title').innerText =
            'Stock Card - ' + medicineName;

        document.getElementById('sc-batch').innerText =
            'Batch: ' + batch.batch_number;

        document.getElementById('sc-item').innerText =
            medicineName;

        document.getElementById('sc-expiry').innerText =
            batch.expiry_date || 'Not Set';

        document.getElementById('sc-batchnum').innerText =
            batch.batch_number;

        document.querySelector(
            '#stockCardModal .mode-val'
        ).innerText =
            batch.remarks || 'Not Specified';

        document.getElementById(
            'sc-table-body'
        ).innerHTML = `

            <tr>

                <td>
                    ${
                        batch.created_at
                            ? new Date(
                                batch.created_at
                            ).toLocaleDateString()
                            : '-'
                    }
                </td>

                <td class="received-value">
                    ${batch.quantity}
                </td>

                <td class="issued-value">
                    0
                </td>

                <td class="balance-value">
                    ${batch.quantity}
                </td>

                <td>
                    ${
                        batch.remarks ||
                        'Initial Stock Entry'
                    }
                </td>

            </tr>

        `;
    }


    function handleExpiredViewDetails(el) {

        const idx =
            parseInt(
                el.getAttribute('data-index'),
                10
            );

        const batch =
            window.currentExpiredBatches[idx];

        if (!batch) {
            return;
        }

        viewBatch(
            batch.medicine_name,
            JSON.stringify(batch)
        );
    }


    function closeStockCard() {

        const modal =
            document.getElementById('stockCardModal');

        modal.classList.remove('show');

        setTimeout(() => {
            modal.style.display = 'none';
        }, 200);
    }


    /* Close stock card when clicking outside */
    document.getElementById('stockCardModal')
        .addEventListener('click', function(e) {

            if (e.target === this) {
                closeStockCard();
            }
        });


    /* =========================================================
       INITIALIZE
    ========================================================= */

    document.addEventListener('DOMContentLoaded', function() {

        loadExpiredBatches();

    });


    /* ESC KEY CLOSES MODALS */
    document.addEventListener('keydown', function(e) {

        if (e.key === 'Escape') {

            soCloseDetails();
            closeStockCard();

        }

    });
</script>

</body>
</html>