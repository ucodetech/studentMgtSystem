@extends('layouts.app')
@section('content')
<div id="carouselId" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active" aria-current="true" aria-label="First slide"></li>
        <li data-bs-target="#carouselId" data-bs-slide-to="1" aria-label="Second slide"></li>
        <li data-bs-target="#carouselId" data-bs-slide-to="2" aria-label="Third slide"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
            <img src="https://www.shutterstock.com/shutterstock/photos/2121997157/display_1500/stock-vector-concept-of-learning-program-study-plan-class-schedule-students-scheduling-courses-plan-students-2121997157.jpg" class="w-100 d-block" height="500" alt="First slide">
            <div class="carousel-caption d-none d-md-block bg-dark rounded-10">
                <h3 class="text-white">Scheduling system</h3>
                <p>Overall, an online class scheduling system enhances the educational experience by providing an efficient and user-friendly platform for students, instructors, and administrators to manage and participate in educational programs.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://www.shutterstock.com/shutterstock/photos/1502134262/display_1500/stock-vector-online-learning-choice-of-courses-exam-preparation-home-schooling-education-training-courses-1502134262.jpg" class="w-100 d-block" height="500" alt="Second slide">
            <div class="carousel-caption d-none d-md-block bg-dark">
                <h3>Keep track of class and time management</h3>
                <p>Overall, an online class scheduling system enhances the educational experience by providing an efficient and user-friendly platform for students, instructors, and administrators to manage and participate in educational programs.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="https://i0.wp.com/www.lovelycoding.org/wp-content/uploads/2022/09/Attendance-Management-System.webp?fit=640%2C427&ssl=1" class="w-100 d-block" height="500" alt="Third slide">
            <div class="carousel-caption d-none d-md-block bg-dark">
                <h3>Attendance system</h3>
                <p>An attendance system is a software or hardware solution used to track and manage the attendance of individuals, such as employees, students, or event participants. It helps organizations and institutions efficiently record attendance, monitor punctuality, and generate reports for various purposes. </p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<div class="container px-4 py-5" id="custom-cards">
    <h2 class="pb-2 border-bottom text-center">WELCOME TO COMPUTER SCIENCE ONLINE CLASS SCHEDULING AN ATTENDANCE SYSTEM</h2>
    <p class="text-bold text-lg lead text-center">An online class scheduling system is a software solution designed to streamline the process of organizing and managing classes, courses, and educational programs in an online or traditional educational institution. It offers a range of tools and features that benefit students, instructors, administrators, and institutions as a whole.</p>

    <div class="row row-cols-1 row-cols-lg-3 align-items-stretch g-4 py-5">
      <div class="col">
        <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTk7QtNYScrmzlU40Pz0xXvQrJrwAf-Jj4FzlcJZwMDLorIkqZ7Uj7wEHNhplwTSLn3N-A&usqp=CAU');">
          <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
            <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Class Schedule</h3>
            <ul class="d-flex list-unstyled mt-auto">
              <li class="me-auto">
                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32" class="rounded-circle border border-white">
              </li>
              <li class="d-flex align-items-center me-3">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#geo-fill"></use></svg>
                <small>Lorem ipsum</small>
              </li>
              <li class="d-flex align-items-center">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#calendar3"></use></svg>
                <small>3d</small>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRiroEfwDBVgcsqI4B6iqqCLqmbak7kv-8feJLaQVJU0DKOfb8ltqyQ0_p1Iew61uj9THc&usqp=CAU');">
          <div class="d-flex flex-column h-100 p-5 pb-3 text-white text-shadow-1">
            <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Attendance</h3>
            <ul class="d-flex list-unstyled mt-auto">
              <li class="me-auto">
                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32" class="rounded-circle border border-white">
              </li>
              <li class="d-flex align-items-center me-3">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#geo-fill"></use></svg>
                <small>Pakistan</small>
              </li>
              <li class="d-flex align-items-center">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#calendar3"></use></svg>
                <small>4d</small>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card card-cover h-100 overflow-hidden text-bg-dark rounded-4 shadow-lg" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDsjEZAU4amAfTNftLSCB13FE_UkOak6EjYhSzl1ixN0q_EHBkJKNeFAjCdugul0YNOlA&usqp=CAU');">
          <div class="d-flex flex-column h-100 p-5 pb-3 text-shadow-1">
            <h3 class="pt-5 mt-5 mb-4 display-6 lh-1 fw-bold">Tracking Records</h3>
            <ul class="d-flex list-unstyled mt-auto">
              <li class="me-auto">
                <img src="https://github.com/twbs.png" alt="Bootstrap" width="32" height="32" class="rounded-circle border border-white">
              </li>
              <li class="d-flex align-items-center me-3">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#geo-fill"></use></svg>
                <small>California</small>
              </li>
              <li class="d-flex align-items-center">
                <svg class="bi me-2" width="1em" height="1em"><use xlink:href="#calendar3"></use></svg>
                <small>5d</small>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection