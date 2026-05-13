<div class="modal fade" id="{{$idModal}}" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-lg">
        <div class="modal-content-siger">
            <div class="modal-header-recuso">
                <h5 class="modal-title">{{$titulo}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>   
</div>