<style>
    .product-admin-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 30px;
        color: #1a1f36;
    }

    .product-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .product-page-header h1 {
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 8px;
        color: #1a1f36;
    }

    .product-page-header p {
        color: #718096;
        margin: 0;
        line-height: 1.6;
    }

    .product-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .product-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #1a1f36;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .product-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(26, 31, 54, 0.08);
        color: #1a1f36;
        text-decoration: none;
    }

    .product-btn.primary {
        background: #d4af37;
        border-color: #d4af37;
    }

    .product-btn.danger {
        background: #f56565;
        border-color: #f56565;
        color: #fff;
    }

    .product-alert {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    .product-alert.success {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.25);
    }

    .product-alert.error {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.25);
    }

    .product-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .product-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 18px;
        box-shadow: 0 4px 10px rgba(26, 31, 54, 0.05);
    }

    .product-stat span {
        display: block;
        color: #718096;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-stat strong {
        display: block;
        font-size: 24px;
        margin-top: 8px;
    }

    .product-toolbar {
        display: grid;
        grid-template-columns: 1.5fr 220px 220px auto;
        gap: 12px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 10px rgba(26, 31, 54, 0.05);
    }

    .product-input,
    .product-select,
    .product-textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 15px;
        color: #1a1f36;
        background: #fff;
    }

    .product-input:focus,
    .product-select:focus,
    .product-textarea:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.14);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: 20px;
    }

    .product-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
        display: flex;
        flex-direction: column;
    }

    .product-card-media {
        height: 170px;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 42px;
        overflow: hidden;
    }

    .product-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .product-card-title {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
    }

    .product-card-title h3 {
        margin: 0;
        font-size: 19px;
        line-height: 1.35;
    }

    .product-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .product-badge.active {
        background: rgba(72, 187, 120, 0.12);
        color: #2f855a;
    }

    .product-badge.inactive {
        background: rgba(245, 101, 101, 0.12);
        color: #c53030;
    }

    .product-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .product-meta div {
        background: #f7fafc;
        border-radius: 8px;
        padding: 10px;
    }

    .product-meta span {
        display: block;
        color: #718096;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .product-meta strong {
        font-size: 15px;
    }

    .product-card-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        padding-top: 4px;
    }

    .product-card-actions.single {
        grid-template-columns: 1fr;
    }

    .product-pagination {
        margin-top: 26px;
    }

    .product-empty-icon {
        font-size: 42px;
        color: #d4af37;
        margin-bottom: 14px;
    }

    .product-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        color: #718096;
    }

    .product-form-card,
    .product-detail-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(26, 31, 54, 0.06);
    }

    .product-detail-actions,
    .product-detail-meta {
        margin-bottom: 18px;
    }

    .product-description {
        color: #4a5568;
        line-height: 1.7;
    }

    .product-section {
        margin-top: 24px;
    }

    .product-form-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 22px;
    }

    .product-form-fields {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .product-toolbar.compact {
        grid-template-columns: 1fr 220px auto;
    }

    .product-field {
        margin-bottom: 16px;
    }

    .product-field label {
        display: block;
        font-weight: 800;
        margin-bottom: 8px;
        color: #1a1f36;
    }

    .product-field small,
    .product-error {
        display: block;
        margin-top: 6px;
        color: #e53e3e;
        font-size: 13px;
        font-weight: 600;
    }

    .product-field .product-help {
        color: #718096;
    }

    .product-preview {
        border-radius: 8px;
        background: #f7fafc;
        min-height: 280px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 56px;
    }

    .product-preview img {
        width: 100%;
        height: 100%;
        min-height: 280px;
        object-fit: cover;
    }

    .product-detail-layout {
        display: grid;
        grid-template-columns: 420px 1fr;
        gap: 22px;
    }

    .product-detail-media {
        height: 420px;
        border-radius: 8px;
        overflow: hidden;
        background: #f7fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d4af37;
        font-size: 72px;
    }

    .product-detail-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 14px;
    }

    .product-table th,
    .product-table td {
        padding: 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
    }

    .product-table th {
        color: #718096;
        font-size: 13px;
        text-transform: uppercase;
    }

    .product-table-empty {
        text-align: center;
        color: #718096;
    }

    @media (max-width: 960px) {
        .product-stats,
        .product-toolbar,
        .product-form-grid,
        .product-detail-layout {
            grid-template-columns: 1fr;
        }

        .product-form-fields,
        .product-toolbar.compact {
            grid-template-columns: 1fr;
        }

        .product-card-actions {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .product-admin-page {
            padding: 20px;
        }

        .product-page-header h1 {
            font-size: 26px;
        }
    }
</style>
