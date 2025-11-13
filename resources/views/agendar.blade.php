
<section id="appointment" class="appointment section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Agenda</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <form action="forms/appointment.php" method="post" role="form" class="php-email-form">
          <div class="row">
            <div class="col-md-4 form-group">
              <input type="text" name="name" class="form-control" id="name" placeholder="Nombre Completo" required="">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico" required="">
            </div>
            <div class="col-md-4 form-group mt-3 mt-md-0">
              <input type="tel" class="form-control" name="phone" id="phone" placeholder="Telefono" required="">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 form-group mt-3">
              <input type="datetime-local" name="date" class="form-control datepicker" id="date" placeholder="Appointment Date" required="">
            </div>
                <div class="col-md-4 form-group mt-3">
                <select name="doctor" id="doctor" class="form-select" required="">
                <option value="">Seleccione Categoria</option>
                <option value="Doctor 1">Uñas</option>
                <option value="Doctor 2">Pestañas</option>
                <option value="Doctor 3">Cejas</option>
                <option value="Doctor 3">Estética Facial</option>
            </select>
            </div>
            <div class="col-md-4 form-group mt-3">

            <label for="id"class="form-label">Seleccionar Servicio</label>
              <select name="id" id="id" class="form-select @error('id') is-invalid @enderror" required>
                <option value="" disabled selected>Seleccione Servico</option>
                @foreach($servicios as $servicio)
                <option value="{$servicio->id}"></option>
                @endforeach
              </select>
            </div>
        
            <div class="col-md-4 form-group mt-3">
              <select name="doctor" id="doctor" class="form-select" required="">
                <option value="">Seleccione Profesionales</option>
                <option value="Doctor 1">Doctor 1</option>
                <option value="Doctor 2">Doctor 2</option>
                <option value="Doctor 3">Doctor 3</option>
                <option value="Doctor 3">Doctor 4</option>
              </select>
            </div>
          </div>

          <div class="form-group mt-3">
            <textarea class="form-control" name="message" rows="5" placeholder="Mensaje(Opcion)"></textarea>
          </div>
          <div class="mt-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your appointment request has been sent successfully. Thank you!</div>
            <div class="text-center"><button type="submit">Agenda Aqui</button></div>
          </div>
        </form>

      </div>

    </section><!-- /Appointment Section -->