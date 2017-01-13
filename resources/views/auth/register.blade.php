@extends('layouts.app')

@section('content')
    <style>
        body {
            background-image: url({{asset('images/register_background.jpg')}});
        }

        .well {
            background: unset;
            border: unset;
        }
    </style>
<div class="container" id="register-section">
    <div class="row" ng-controller="RegisterController" ng-init="init()">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                {{--<div class="panel-heading">Rejestracja</div>--}}
                <div class="panel-body">
                    <h1>Rejestracja</h1>
                    <hr>
					
					<div class="col-md-offset-2 col-md-8">
						<div class="alert alert-warning" ng-if="userGeolocation === undefined">
							<strong><i class="spinning fa fa-cog"></i> Trwa pobieranie Twojej geolokalizacji.</strong>
						</div>
						<div class="alert alert-danger" ng-if="userGeolocation === false">
							<strong><i class="fa fa-times"></i> Wystąpił problem z wykrywaniem twojej geolokalizacji. Podejmij poniższe kroki:</strong>
							<ul>
								<li>Zaakceptuj udostępnianie swojego położenia tej witrynie</li>
								<li>Jeśli pytanie o udostępnienie położenia ciągle się pojawia, wybierz opcję "Zawsze zezwalaj tej witrynie"</li>
								<li>Sprawdź czy Twoja przeglądarka obsługuje funkcję navigator.geolocation.getCurrentPosition</li>
								<li>Sprawdź czy wszedłeś na stronę wykorzystując bezpieczny protokół HTTPS</li>
								<li>DANE PRZECHOWYWANE SĄ TYLKO I WYŁĄCZNIE W CELACH EDUKACYJNYCH</li>
							</ul>
						</div>
						<div class="alert alert-success" ng-if="userGeolocation">
							<strong><i class="fa fa-check"></i> Pomyślnie pobrano Twoją geolokalizację, możesz się zarejestrować i w pełni korzystać z portalu.</strong>
						</div>
					</div>
				
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">Imię</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>

                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Nazwisko</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>

                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Płeć</label>

                            <div class="col-md-6">
                                <label class="radio-inline"><input type="radio" name="sex" value="m" required>Mężczyzna</label>
                                <label class="radio-inline"><input type="radio" name="sex" value="f" required>Kobieta</label>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">Miasto</label>

                            <div class="col-md-6">

                                {{--<input autocomplete="off" type="text" id="city" class="form-control" name="city" value="{{ old('city') }}" ng-model="phrase.value">--}}

								<small ng-if="citySelected === false"><i class="fa fa-info"></i> Zacznij wpisywać nazwę miasta, a następnie wybierz z listy klikając na nie.</small>
                                <input class="form-control" type="text" autocomplete="off" id="city" placeholder="Wyszukaj miasto" ng-model="phrase" name="city" ng-focus="focus()" ng-blur="focus()" required>

                                {{--<div ng-if="!citySelected" id="city-select">--}}
                                <div ng-if="show && cities.length" id="city-select">
                                   <ul>
                                    <li ng-click="selectCity(city.name, city.id)" ng-repeat="city in cities" style="cursor:pointer"><% city.name %> <small><% city.distance %>km stąd</small></li>
                                   </ul>
                                </div>

                                <input type="hidden" name="city_id" value="<% cityIdSelected %>" required>


                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('birthday') ? ' has-error' : '' }}" >
                            <label for="birthday" class="col-md-4 control-label">Data urodzenia</label>

                            <div class="col-md-6" >
                                <div uib-datepicker ng-model="dt" class="well well-sm" datepicker-options="options"></div>
                                <input type="hidden" name="birthday" value="<% birthday %>" required>

                                @if ($errors->has('birthday'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('birthday') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Hasło</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Powtórz hasło</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button ng-disabled="userGeolocation === undefined || citySelected === false" type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i> Zarejestruj
                                </button>
                            </div>
						
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
