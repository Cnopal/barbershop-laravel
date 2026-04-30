<style>
    .shop-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .shop-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .shop-header.section {
        margin-top: 34px;
    }

    .shop-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
        font-weight: 800;
        color: #1a1f36;
    }

    .shop-header h1.compact {
        font-size: 26px;
    }

    .shop-header p {
        margin: 0;
        color: #718096;
        line-height: 1.6;
    }

    .shop-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #1a1f36;
        text-decoration: none;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .shop-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(26, 31, 54, 0.08);
        color: #1a1f36;
        text-decoration: none;
    }

    .shop-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .shop-alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 700;
    }

    .shop-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .shop-alert.error {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.25);
    }

    .shop-pagination {
        margin-top: 26px;
    }

    .shop-toolbar {
        display: grid;
        grid-template-columns: 1fr 240px auto;
        gap: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 22px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .shop-toolbar.order-toolbar {
        grid-template-columns: minmax(250px, 1.4fr) minmax(145px, 0.65fr) minmax(145px, 0.65fr) minmax(180px, 0.85fr) minmax(160px, 0.75fr) auto auto;
        align-items: center;
    }

    .order-search-field {
        position: relative;
    }

    .order-search-field i {
        position: absolute;
        left: 14px;
        top: 50%;
        color: #718096;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .order-search-field .shop-input {
        padding-left: 40px;
    }

    .shop-input,
    .shop-select {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 15px;
        color: #1a1f36;
        background: #fff;
    }

    .shop-input:focus,
    .shop-select:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.14);
    }

    .shop-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: 20px;
    }

    .shop-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
    }

    .shop-card:hover {
        transform: translateY(-4px);
        border-color: #d4af37;
        box-shadow: 0 10px 22px rgba(26, 31, 54, 0.10);
    }

    .shop-card-media {
        height: 190px;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 46px;
        overflow: hidden;
    }

    .shop-card-media img,
    .shop-detail-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .shop-card-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .shop-category {
        display: inline-flex;
        align-self: flex-start;
        background: rgba(212, 175, 55, 0.14);
        color: #8a6d16;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 900;
    }

    .shop-card h3 {
        margin: 0;
        font-size: 20px;
        line-height: 1.35;
    }

    .shop-card p {
        color: #718096;
        line-height: 1.55;
        margin: 0;
        flex: 1;
    }

    .shop-card-footer {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        margin-top: 4px;
    }

    .shop-card-footer.summary {
        margin-bottom: 18px;
    }

    .shop-inline-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .shop-price {
        font-size: 22px;
        font-weight: 900;
        color: #d4af37;
    }

    .shop-stock {
        color: #718096;
        font-size: 13px;
        font-weight: 700;
    }

    .shop-detail-layout {
        display: grid;
        grid-template-columns: 420px minmax(0, 1fr);
        gap: 22px;
        align-items: start;
    }

    .shop-detail-media {
        height: 420px;
        background: #f7fafc;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 72px;
        border: 1px solid #e2e8f0;
    }

    .shop-detail-card,
    .shop-order-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .shop-detail-card h1 {
        margin: 8px 0 12px;
        font-size: 32px;
        font-weight: 900;
    }

    .shop-description {
        color: #4a5568;
        line-height: 1.8;
        margin-bottom: 22px;
    }

    .shop-buy-box {
        background: #f7fafc;
        border-radius: 8px;
        padding: 16px;
        display: grid;
        gap: 14px;
    }

    .shop-qty-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 14px;
        align-items: center;
    }

    .shop-empty {
        grid-column: 1 / -1;
        background: #fff;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        padding: 60px 20px;
        text-align: center;
        color: #718096;
    }

    .shop-empty i {
        font-size: 44px;
        color: #d4af37;
        margin-bottom: 12px;
    }

    .shop-order-list {
        display: grid;
        gap: 16px;
    }

    .shop-order-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: center;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
        color: #1a1f36;
        text-decoration: none;
    }

    .shop-order-row:hover {
        color: #1a1f36;
        text-decoration: none;
        border-color: #d4af37;
    }

    .shop-order-row strong,
    .shop-order-row span {
        display: block;
    }

    .shop-order-row span {
        color: #718096;
        margin-top: 5px;
    }

    .shop-order-products {
        display: block;
        margin-top: 7px;
        color: #4a5568;
        font-weight: 700;
        line-height: 1.45;
    }

    .shop-order-date {
        margin: 10px 0 0;
        color: #718096;
    }

    .shop-order-note {
        margin-top: 18px;
        color: #718096;
    }

    .shop-status {
        display: inline-flex;
        border-radius: 999px;
        padding: 6px 11px;
        font-size: 12px;
        font-weight: 900;
        background: #edf2f7;
        color: #4a5568;
        white-space: nowrap;
    }

    .shop-status.paid {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
    }

    .shop-status.pending_payment {
        background: rgba(237, 137, 54, 0.14);
        color: #c05621;
    }

    .shop-status.cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .shop-order-badges {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .shop-status.order-received {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
    }

    .shop-status.order-pending,
    .shop-status.order-processing {
        background: rgba(237, 137, 54, 0.14);
        color: #c05621;
    }

    .shop-status.order-ready_for_pickup {
        background: rgba(66, 153, 225, 0.14);
        color: #2b6cb0;
    }

    .shop-status.order-needs_review {
        background: rgba(159, 122, 234, 0.14);
        color: #6b46c1;
    }

    .shop-status.order-cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .shop-order-progress {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin: 18px 0 22px;
    }

    .shop-order-step {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 14px;
        color: #718096;
        background: #fff;
    }

    .shop-order-step span {
        display: inline-flex;
        width: 28px;
        height: 28px;
        border-radius: 999px;
        align-items: center;
        justify-content: center;
        background: #edf2f7;
        font-weight: 900;
        margin-bottom: 10px;
    }

    .shop-order-step strong {
        display: block;
    }

    .shop-order-step.active {
        border-color: rgba(72, 187, 120, 0.35);
        color: #1a1f36;
        background: rgba(72, 187, 120, 0.08);
    }

    .shop-order-step.current {
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.12);
    }

    .shop-order-state {
        grid-column: 1 / -1;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
    }

    .shop-order-state.review {
        background: rgba(159, 122, 234, 0.14);
        color: #6b46c1;
    }

    .shop-order-state.cancelled {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .shop-order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
    }

    .shop-order-table th,
    .shop-order-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .shop-order-table th {
        color: #718096;
        text-transform: uppercase;
        font-size: 13px;
    }

    .shop-order-table tfoot td {
        font-weight: 900;
        font-size: 18px;
        border-bottom: none;
    }

    .shop-order-table tfoot td:last-child {
        color: #d4af37;
    }

    @media (max-width: 960px) {
        .shop-toolbar,
        .shop-toolbar.order-toolbar,
        .shop-detail-layout {
            grid-template-columns: 1fr;
        }

        .shop-detail-media {
            height: 360px;
        }
    }

    @media (max-width: 600px) {
        .shop-page {
            padding: 20px;
        }

        .shop-header h1,
        .shop-detail-card h1 {
            font-size: 28px;
        }

        .shop-order-row,
        .shop-qty-row,
        .shop-order-progress {
            grid-template-columns: 1fr;
        }

        .shop-inline-actions {
            width: 100%;
        }
    }
</style>
