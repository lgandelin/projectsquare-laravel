<div class="modal fade" id="create-conversation-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Créer une conversation</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input class="form-control" type="text" placeholder="{{ trans('projectsquare::conversations.title') }}" name="title" />
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" placeholder="{{ trans('projectsquare::conversations.message') }}" name="message" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn valid-create-conversation"><i class="glyphicon glyphicon-ok"></i> Valider</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>