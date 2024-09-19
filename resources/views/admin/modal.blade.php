<div class="modal  fade" id="ajaxModal" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-dark text-16px" id="ajaxModalLabel"></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="w-100 text-center py-5">
                    <div class="spinner-border my-5" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn ol-btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModal" aria-labelledby="ajaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content pt-2">
            <div class="modal-body text-center">
                <div class="icon icon-confirm">
                    <i class="fi-rr-exclamation"></i>
                </div>
                <p class="title">{{ get_phrase('Are you sure?') }}</p>
                <p class="text-muted">{{ get_phrase("You can't bring it back!") }}</p>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn ol-btn-secondary fw-500" data-bs-dismiss="modal">{{ get_phrase('Cancel') }}</button>
                <a href="" class="confirm-btn btn ol-btn-success fw-500">{{ get_phrase('Confirm') }}</a>
            </div>
        </div>
    </div>
</div>


<div class="offcanvas offcanvas-end" tabindex="-1" id="right-modal" aria-labelledby="right-modalLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title title text-16px" id="right-modalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
    </div>
</div>

<script>
    "use strict";

    function showRightModal(url, header) {
        // SHOWING AJAX PRELOADER IMAGE
        jQuery('#right-modal .offcanvas-body').html(
            '<div class="modal-spinner-border"><div class="spinner-border text-secondary" role="status"></div></div>'
        );
        jQuery('#right-modal .offcanvas-title').html("{{ get_phrase('Loading') }}...");
        // LOADING THE AJAX MODAL


        const bsOffcanvas = new bootstrap.Offcanvas('#right-modal');
        bsOffcanvas.show();

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function(response) {
                jQuery('#right-modal .offcanvas-title').html(header);
                jQuery('#right-modal .offcanvas-body').html(response);

            }
        });
    }
</script>

<script type="text/javascript">
    "use strict";

    function ajaxModal(url, title, modalClasses = 'modal-md', animation = 'fade') {
        $('#ajaxModal .modal-dialog').removeClass('modal-sm');
        $('#ajaxModal .modal-dialog').removeClass('modal-md');
        $('#ajaxModal .modal-dialog').removeClass('modal-lg');
        $('#ajaxModal .modal-dialog').removeClass('modal-xl');
        $('#ajaxModal .modal-dialog').removeClass('modal-xxl');
        $('#ajaxModal .modal-dialog').removeClass('modal-fullscreen');
        $('#ajaxModal .modal-dialog').addClass(modalClasses);

        $('#ajaxModal').removeClass('fade');
        $('#ajaxModal').addClass(animation);

        $('#ajaxModal .modal-title').html(title);
        $("#ajaxModal").modal('show');
        $.ajax({
            type: 'get',
            url: url,
            success: function(response) {
                $('#ajaxModal .modal-body').html(response);
            }
        });
    }

    const myModalEl = document.getElementById('ajaxModal')
    myModalEl.addEventListener('hidden.bs.modal', event => {
        $('#ajaxModal .modal-body').html(
            '<div class="w-100 text-center py-5"><div class="spinner-border my-5" role="status"><span class="visually-hidden"></span></div></div>'
        );
    })



    function confirmModal(url, elem = false, actionType = null, content = null) {
        $("#confirmModal").modal('show');

        if (elem != false) {
            $.ajax({
                url: url,
                success: function(response) {
                    response = JSON.parse(response);
                    //For redirect to another url
                    if (typeof response.success != "undefined") {
                        window.location.href = response.success;
                    }
                    distributeServerResponse(response);
                }
            });
        } else {
            $('#confirmModal .confirm-btn').attr('href', url);
            $('#confirmModal .confirm-btn').removeAttr('onclick');
        }
    }
</script>