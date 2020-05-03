@extends('layouts.home')
@section('content')
    <div class="home-box">
        
        <h2 title="{{ $site_title or 'title' }}" style="margin: 0;">
           
            
        </h2>
        <h3 title="{{ $description or 'description' }}" aria-hidden="true" style="margin: 0">
            {{ 'WELCOME IN H.I.J'}} <br>
           MEDIUM INDONESIA <br><br>
         
        </h3>
        <h4 title="{{ $description or 'description' }}" aria-hidden="true" style="margin: 0">
            H = Hery B.P. Sihaloho <br>
            I = Ibnu Afandi <br>
            J = Jemi Yantika Damanik
        </h3>
      

        <p  class="links" style="text-align:center">
           
            <span  aria-hidden="true">»</span>
            <a style= "center" href="{{ route('post.index') }}" aria-label="CLICK_SEE_LIST">GET STARTED</a>
            @foreach($pages as $page)
                <span aria-hidden="true">/</span>
                <a href="{{ route('page.show',$page->name) }}" aria-label="{{__('web.CHECK')}}{{ $author or 'author' }}{{__('web.`S')}}{{ $page->display_name }}">{{$page->display_name }}</a>
            @endforeach
             
        </p>
  
    </div>
@endsection