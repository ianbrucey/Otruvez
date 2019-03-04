@extends('layouts.app')

@section('body')
        <section class="sell-with-o-section popular-deals section bg-white">
            <div class="container ">
                <div class="row">

                    <div class="col-md-12 mb-0">
                        {{--<h1 class="text-center"><img src="{{ getOtruvezLogoImg() }}" width="200"></h1>--}}
                        <h1 class="text-center">Consumer FAQs</h1>
                        <ol>
                            <li>
                                <br>
                                <h5>How many times can I use a subscription?</h5>
                                <p>
                                    If there is a limit imposed on a subscription, it will be listed
                                    under the <span class="theme-color"><b>"Usage Limit"</b></span> section on the checkout page for that subscription.
                                    You can use that subscription up to the limit in whatever month you're in.
                                    The limit will reset each month.
                                </p>

                            </li>
                            <li>
                                <br>
                                <h5>I'm not satisfied with the subscription I bought, can I get a refund?</h5>
                                <p>
                                    If you are unsatisfied with a product or service, that business providing the service
                                    can issue you a refund and cancel your subscription if you and they agree to it.
                                    If you cannot agree, you can escalate your issue by contacting support.
                                    If you can provide proof that the business did not provide the service
                                    as described in their description, the <span class="theme-color"><b><a href="/contact">Otruvez support team</a> </b></span>
                                    will issue you a refund on our end and cancel your subscription.
                                </p>

                            </li>
                            <li>
                                <br>
                                <h5>What happens if the business deletes their account, goes out of
                                    business or stops offering a service I was
                                    subscribed to? Can they keep my money?
                                </h5>
                                <p>
                                    If a subscription you were previously subscribed to is no longer available
                                    and you were charged for it in the current month,
                                    we will refund you the amount of the subscription multiplied by the percentage
                                    of usage that you have left on that subscription. for example:
                                </p>
<pre class="p-3 theme-background text-white">
( $100 subscription cost ) * (2 uses remaining / limit of 4 uses) = refund amount of $50;
</pre>

                            </li>
                            <li>
                                <br>
                                <h5>How do I cancel my subscription?</h5>
                                <p>
                                    You can cancel your subscription by going to <span class="theme-color"><b>My Account > My Subscriptions</b></span><br>
                                    Then find the read cancel button
                                </p>

                            </li>
                            <li>
                                <br>
                                <h5>Is my card info safe?</h5>
                                <p>
                                    Yes. Otruvez does not store any of your card information on our servers other than the last 4 digits and the type of card.
                                    The rest of your card info is stored with Stripe which is a dedicated and secure credit card processing company
                                </p>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

@endsection





