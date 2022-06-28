@extends('auth.contenido')

@section('login')

<div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-group mb-0">
        <div class="card p-4">
        <form class="form-horizontal was-validated" method="POST" action="{{ route('login') }}">
          {{csrf_field()}}
            <div class="card-body">
            <div><img src="img/HACEM.png" alt="" style="width:100%"></div><br>
            <!--<p class="text-muted">Control de acceso al sistema</p>-->

            <div class="form-group mb-3{{$errors->has('email' ? 'is-invalid' : '')}}">
              <span class="input-group-addon"><i class="icon-user"></i></span>
            <input type="email" value="{{old('email')}}" name="email" id="email" class="form-control" placeholder="Correo">
              {!!$errors->first('email','<span class="invalid-feedback">:message</span>')!!}
            </div>

            <div class="form-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
              <span class="input-group-addon"><i class="icon-lock"></i></span>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password">
              {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
            </div>

            <div class="row">
              <div class="col-6">
                <button type="submit" class="btn btn-primary px-4">Acceder</button>
              </div>
            </div>
          </div>
        </form>
        </div>
        <!--<div class="card portada">-->
        <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <div class="card-body text-center">
        <!-- -->
            <div>
              <h2>H.A.C.E.M - SENA CIDM</h2>
              <p>Herramienta de apoyo para el costeo en las empresas manufactureras.</p>
              <p>Confecciones.</p>
              <p><img src="img/logo-symbol.png" alt=""></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

<style>
.portada{
    background: url(img/portada.jpg);
    background-repeat: no-repeat;
    background-size: cover;

 }
</style>
