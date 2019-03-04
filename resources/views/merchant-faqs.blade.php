@extends('layouts.app')

@section('body')

    <section class="sell-with-o-section popular-deals section bg-white">
        <div class="container ">
            <div class="row">

                <div class="col-md-12 mb-0">
{{--                    <h1 class="text-center"><img src="{{ getOtruvezLogoImg() }}" width="200"></h1>--}}
                    <h1 class="text-center">Merchant FAQs</h1>
                    <ol>
                        <li>
                            <br>
                            <h5>Alright, what's the fee for the service?</h5>
                            <p>
                               10% per subscription you acquire. Our credit card processing company takes a small percentage and we take the difference to make up 10.<br>
                                The fee is negotiable however based on volume. <a href="/contact"><span class="theme-color"><b>Contact our sales team</b></span></a>  for more information. We will get back to you promptly.
                            </p>
                            {{--need to reroute logged in traffic for /contact--}}

                        </li>
                        <li>
                            <br>
                            <h5>How often do I get paid?</h5>
                            <p>
                                We payout every 2 weeks.
                            </p>
                            {{--need to reroute logged in traffic for /contact--}}

                        </li>
                        <li>
                            <br>
                            <h5>How do I cancel my merchant account?</h5>
                            <p>
                                You may cancel your account by <a href="/contact">contacting support</a>. In your subject line, please include <span class="theme-color"><b>"Business Deletion Request"</b></span>
                                    so that we may identify you faster
                            </p>
                        </li>
                        <li>
                            <br>
                            <h5>Can I set a limit on how many times a consumer can use my service or product?</h5>
                            <p>
                                Absolutely. During the creation of a subscription, you can set the limit on the interval of a month or year.
                                So, if you want, a subscription can be set to a limit of 5 times a month or 5 times year.
                            </p>
                        </li>
                        <li>
                            <br>
                            <h5>What happens to my money after I delete my account? Are refunds given out or can I keep the money?
                            </h5>
                            <p>
                                To protect our consumers, if you delete your account AND the consumer has already been charged within
                                the current month or year of the limit interval you set for that subscription, we will refund them the
                                cost of the subscription multiplied by the percentage of usage that they have left on that subscription. If they
                                have used your service to the limit within the interval, then no refund will be paid out to them
                                For example:
                            </p>
<pre class="p-3 theme-background text-white">
( $100 subscription cost ) * (2 uses remaining / limit of 4 uses per month) = refund amount of $50;
</pre>

                        </li>
                        <li>
                            <br>
                            <h5>My customer is demanding a refund, how do I handle this?</h5>
                            <p>
                                You may offer them a refund on your own if you'd like to deescalate the issue quickly.
                                <br> You can do this by going to <span class="theme-color"><b>Business Account > Active Subscribers</b></span> and then filtering
                                to their subscription on that page where the refund button will be. As a result of manually refunding them, their subscription will also
                                be cancelled.<br> please note that if they escalate to <span class="theme-color"><b>Otruvez Support</b></span>, we will force a refund if it is
                                determined with evidence that you did not provide the service you described. However, we will not refund them simply because they did not
                                like what they received.
                            </p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

@endsection





