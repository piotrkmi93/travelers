<div id="bug-report-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Zgłoś błąd</h4>
            </div>

            <form ng-controller="BugReportController" ng-init="init({{Auth::user()->id}})">
                <div class="modal-body">
                    <p>Jeżeli odkryłeś jakiś większy lub mniejszy błąd na tej stronie, możesz go zgłosić do twórcy. Dzięki temu portal może być jeszcze lepszy i działać sprawniej. Jeśli chcesz zobaczyć listę błędów zgłoszonych przez innych użytkowników <a href="{{ url('bug_reports') }}">kliknij tutaj</a> </p>
                    <hr>

                    <div class="alert alert-success" ng-show="success == true">
                        <strong>Sukces!</strong> Wysłano zgłoszenie o błędzie
                    </div>

                    <div class="alert alert-danger" ng-show="success == false">
                        <strong>Niepowodzenie!</strong> Nie można wysłać zgłoszenia, prosimy spróbować później
                    </div>

                    <label for="bug-report-description">Opisz błąd (znaków: <% 255 - bug.description.length %>)</label>
                    <textarea id="bug-report-description" class="form-control" ng-model="bug.description" maxlength="255" required></textarea>
                </div>
                <div class="modal-footer">
                    <button ng-click="send()" type="submit" class="btn btn-success">Wyślij</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </form>


        </div>

    </div>
</div>