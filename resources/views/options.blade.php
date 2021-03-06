@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Zmiana hasła</h1>

                        @if(isset($success) && isset($info))
                            @if($success)
                                <div class="alert alert-success">
                                    <i class="fa fa-check"></i> {{ $info }}.
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i> {{ $info }}.
                                </div>
                            @endif
                        @endif

                        <form method="POST" action="{{ url('options/change_password') }}">

                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="current_password">Aktualne hasło</label>
                                <input minlength="6" maxlength="32" autocomplete="off" type="password" name="current_password" id="current_password" class="form-control" placeholder="Wpisz aktualne hasło..." required>
                            </div>

                            <div class="form-group">
                                <label for="password">Nowe hasło</label>
                                <input minlength="6" maxlength="32" autocomplete="off" type="password" name="password" id="password" class="form-control" placeholder="Wpisz nowe hasło..." required>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Potwierdź hasło</label>
                                <input minlength="6" maxlength="32" autocomplete="off" type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Wpisz ponownie nowe hasło..." required>
                            </div>

                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Zmień hasło</button>

                        </form>

                    </div>
                </div>
            </div>
			
			
        </div>
		
		<div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h1>Zmiana danych osobowych</h1>

                        @if(isset($fln_success) && isset($fln_info))
                            @if($fln_success)
                                <div class="alert alert-success">
                                    <i class="fa fa-check"></i> {{ $fln_info }}.
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i> {{ $fln_info }}.
                                </div>
                            @endif
                        @endif

                        <form method="POST" action="{{ url('options/change_names') }}">

                            {{ csrf_field() }}
							 
							<div class="col-md-6 form-group">
								<label>Imię</label>
								<input class="form-control" type="text" name="first_name" placeholder="Wpisz swoje imię..." value="{{Auth::user()->first_name}}" required>
							</div>
							
							<div class="col-md-6 form-group">
								<label>Nazwisko</label>
								<input class="form-control" type="text" name="last_name" placeholder="Wpisz swoje nazwisko..." value="{{Auth::user()->last_name}}" required>
							</div>
							
						
							<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Zmień dane</button>
							
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>
@endsection
