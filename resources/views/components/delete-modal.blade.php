<div class="modal" id="x-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ $heading }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                {{ $body }}
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <form action="" method="POST" id="x-form">
                    @csrf
                    @method('delete')
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </form>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).on('click','.deleteUser',function(){
            const action = $(this).attr('data-route');
            console.log(action)
            $('#x-form').attr('action', action);
            $('#x-modal').modal('show');
        });
    </script>
@endpush
