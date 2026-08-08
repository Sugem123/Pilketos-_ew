<style>
    .swal2-popup-custom {
        border-radius: 1rem !important;
        padding: 2rem 2rem 1.75rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(17, 24, 39, 0.05) !important;
    }
    .swal2-icon-custom {
        border: none !important;
        border-radius: 1rem !important;
        width: 3.5rem !important;
        height: 3.5rem !important;
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
        box-shadow: inset 0 -1px 3px rgba(220, 38, 38, 0.15) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-bottom: 1.25rem !important;
    }
    .swal2-icon-custom .swal2-icon-content {
        font-size: inherit !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .swal2-title-custom {
        color: #111827 !important;
        font-weight: 700 !important;
        font-size: 1.25rem !important;
        line-height: 1.4 !important;
        padding: 0 0 0.5rem !important;
        letter-spacing: -0.01em !important;
    }
    .swal2-html-custom {
        color: #6b7280 !important;
        font-size: 0.95rem !important;
        line-height: 1.6 !important;
        padding: 0 0.75rem 1.5rem !important;
    }
    .swal2-actions-custom {
        display: flex !important;
        justify-content: center !important;
        gap: 0.75rem !important;
        padding: 0 !important;
    }
    .swal2-confirm-custom {
        background: #dc2626 !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.5rem !important;
        min-width: 7.5rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        letter-spacing: 0.01em !important;
        color: #fff !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25), 0 1px 3px rgba(220, 38, 38, 0.15) !important;
    }
    .swal2-cancel-custom {
        background: #fff !important;
        border: 1px solid #e5e7eb !important;
        color: #374151 !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.5rem !important;
        min-width: 7.5rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        letter-spacing: 0.01em !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.2s ease-in-out !important;
    }
    .swal2-cancel-custom:hover {
        background: #f9fafb !important;
        border-color: #d1d5db !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06) !important;
    }
    .swal2-icon-import {
        border: none !important;
        border-radius: 1rem !important;
        width: 3.5rem !important;
        height: 3.5rem !important;
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%) !important;
        box-shadow: inset 0 -1px 3px rgba(22, 163, 74, 0.15) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-bottom: 1.25rem !important;
    }
    .swal2-icon-import .swal2-icon-content {
        font-size: inherit !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .swal2-confirm-import {
        background: #16a34a !important;
        border-radius: 0.75rem !important;
        padding: 0.75rem 1.5rem !important;
        min-width: 7.5rem !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        color: #fff !important;
        cursor: pointer !important;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.25), 0 1px 3px rgba(22, 163, 74, 0.15) !important;
    }
    .swal2-confirm-import:hover {
        background: #15803d !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.confirmDelete = function(url, title, message) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

            Swal.fire({
                title: title,
                html: message,
                iconHtml: '<svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1.6rem;height:1.6rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                backdrop: 'rgba(17, 24, 39, 0.35)',
                customClass: {
                    popup: 'swal2-popup-custom',
                    icon: 'swal2-icon-custom',
                    title: 'swal2-title-custom',
                    htmlContainer: 'swal2-html-custom',
                    actions: 'swal2-actions-custom',
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        };
    });
</script>