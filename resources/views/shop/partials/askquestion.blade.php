<div class="modal" id="modalAskAQuestion" tabindex="-1" role="dialog"  aria-hidden="true" >
		<div class="modal-dialog">
			<div class="modal-content">
                <div class="modal-header">
                    Ask a question
                </div>
                <div class="modal-body">
					<div class="row">
					<form  id="frmAskAQuestion" name="frmAskAQuestion" autocomplete="off" novalidate ng-submit="submitAskAQuestion(frmAskAQuestion.$valid)" >
					{!!Form::token()!!}
                    <div class="alert alert-success" role="alert"  ng-show="aaqSucessMsg">
                        <button type="button" class="close" ng-click="aaqSucessMsg=false" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Question posted sucessfully
                    </div>

					<div class="col-sm-12 form-group">
						<textarea  name="question" class="form-control" placeholder="Question or Query..." ng-model="askquestion.question" required></textarea>
						<span ng-show="frmAskAQuestion.question.$invalid && frmAskAQuestion.$submitted" class="help-block validationError">required.</span>
					</div>
					<div class="col-sm-12">
						<span class="sendBtn" >
                            <input type="submit" ng-show="!aaqLoading" name="submit" class='btn btnSmall' value="Ask">
                            <i class="fa fa-spinner" ng-show="aaqLoading"></i>
                        </span>
					</div>
				</form>
				</div>
				</div>
			</div>
		</div>
	</div>
