<div id="" class="row" role="dialog">
    <div class="col-md-6 offset-md-3">

        <!-- Modal content-->
        <div class="theme-background" style="border-radius: 5%">
            <div class="card-header">
                <h4 class="text-white">Create Business</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <form method="post" action="/business/create" class="form-group-md validate-create-business" id="create-business-form" >
                <div class="card-body theme-form">
                    {{csrf_field()}}
                    <div class="text-white-children business-handle-container">
                        <p data-toggle="collapse" data-target="#business-handle-info"><u>Unique Business Handle</u> <span class="float-right">What's this?</span></p>
                        <p class="p-2 bg-white theme-color collapse" id="business-handle-info" style="">
                            Your business handle will be used so people can quickly access your online store, either through our search engine or via a url like this: <b class="theme-color">OTRUVEZ.COM/STORE/EXAMPLE_STORE</b>
                            <br>
                            <strong class="theme-color">Please note that your handle can and will be revoked if you register a name that you do not rightfully own.</strong>
                        </p>

                        <p id="chosen-handle">{{ !empty(old('business_handle')) ? "You chose @".old('business_handle') : ''}}</p>
                        <input class="form-control" placeholder="Ex: example_store" type="text" id="choose-business-handle" name="choose_business_handle" value="{{old('business_handle')}}">
                        <input type="hidden" id="business-handle" name="business_handle" value="{{old('business_handle')}}">
                        <p class="p-3 text-center"><a class="btn btn-sm bg-white theme-color" onclick="checkHandleAvailability()">Check availability</a></p>
                        <hr>
                    </div>
                    <div class="rest-of-biz-inputs" style="display: none" >
                        <input type="text" name="name" class="form-control" placeholder="Business Name" required value="{{old('name')}}">
                        <input type="email" name="email" class="form-control" placeholder="Business Email" required value="{{old('email')}}">
                        <input type="tel" name="phone" class="form-control" placeholder="Business Phone" required value="{{old('phone')}}">
                        <textarea type="text" name="description" class="form-control" placeholder="Business Description here..." required>{{old('description')}}</textarea>
                        <div class="text-white-children">
                            <hr>
                            <p data-toggle="collapse" data-target="#redirect-url-info"><u>Redirect Url:</u> *optional* <span class="float-right">What's this?</span></p>
                            <p> *optional, for online businesses*</p>
                            <p class="collapse" id="redirect-url-info" >This field is for online businesses who want to use our portal to sell their subscriptions. After a customer completes the subscription process, they will be redirected to this URL. You can also set this field in the <b>API & Online Business Integration</b> Page.</p>
                        </div>
                        <div class="">
                            <input class="form-control" placeholder="www.example.com/thanks" type="text" id="redirect-to-url" name="redirect_to" value="{{old('redirect_to')}}">
                        </div>
                        <hr>
                        @include('partials.location.set-address')

                        <hr>
                        <p class="text-white"><u>Business hours</u>
                            <label class=" pull-right checkbox-inline text-white"><input type="checkbox" class="has-business-hours "> Add store hours</label>
                        </p>
                        <div class="business-hours">
                            @foreach($days as $day)
                                <div style="width: 30%; display: inline-block">
                                    <label class="text-white">{{ucfirst($day)}}</label>
                                </div>
                                <div style="width: 68%; display: inline-block">
                                    <input type="text" name="{{$day}}" class="" value="{{old($day)}}" placeholder="ex: 10am - 8pm ">
                                </div>

                            @endforeach
                        </div>

                    </div>
                </div>
                <div class="card-footer rest-of-biz-inputs text-center" style="display: none">
                    <h3 class="text-white">Professional Conduct and Refund Policy</h3>
                    <div class="text-white-children">
                        <h4><b>Service</b></h4>
                        <p>Participating businesses on the Otruvez Platform MUST provide the service or product as it is written in the description section of any subscription the offer. If a consumer provides evidence that a service or product offered was not provided as described, they may be entitled to a refund. Any negative reviews posted on the Business's store my be contested, but will remain posted if the review is determined to be reasonable. <hr></p>
                        <h4><b>Conduct</b></h4>
                        <p>Any participating businesses found partaking in fraudulent or malicious practices that cause harm to consumers or the Otruvez platform's likeness or image will result in that business being <b>BANNED</b> from the platform. Also, if necessary, Otruvez LLC may pursue <b>LEGAL ACTION</b> against that business or its owner<hr></p>
                        <h4><b>Refunds</b></h4>
                        <p>If a business decides to remove their account from Otruvez, any active subscribers at the time may be entitled to a full or partial refund. This refund is calculated by the percentage of usage left of that subscription according to the limit imposed, multiplied by the cost of the subscription. Checkout any one of our <a href="/merchant-faqs"><u>FAQ</u></a> pages to get more details on how it's calculated</p>
                        <h4><b>MISC</b></h4>
                        <p>You may send us a million dollar check in the mail if you want. If not, we understand.</p>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-default w-100" id="">Create Your Business</button>
                </div>
            </form>
        </div>

    </div>
</div>
