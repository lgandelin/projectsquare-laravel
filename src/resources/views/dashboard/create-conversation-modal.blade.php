<div class="modal fade" id="create-conversation-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{{ __('projectsquare::conversations.create_conversation') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">{{ __('projectsquare::conversations.title') }}</label>
                        <input class="form-control" type="text" placeholder="{{ __('projectsquare::conversations.title') }}" name="title" autocomplete="off" />
                    </div>

                    <div class="form-group">
                        <label for="message">{{ __('projectsquare::conversations.message') }}</label>
                        <textarea class="form-control" placeholder="{{ __('projectsquare::conversations.message') }}" name="message" rows="10" autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn valid valid-create-conversation"><i class="glyphicon glyphicon-ok"></i> {{ __('projectsquare::conversations.validate') }}</button>
                    <button type="button" class="btn" data-dismiss="modal"><span class="glyphicon glyphicon-arrow-left"></span> {{ __('projectsquare::conversations.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>