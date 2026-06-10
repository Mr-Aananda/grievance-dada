<style>
    .loading-spinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .spinner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .spinner {
        position: relative;
        z-index: 10000;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.25em;
    }

    .spinner-text {
        margin-left: 10px;
        color: #333;
        font-size: 1rem;
    }
</style>

<div id="loading-spinner" class="loading-spinner d-none">
    <div class="spinner-overlay"></div>
    <div class="spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="spinner-text">Loading...</span>
    </div>
</div>
