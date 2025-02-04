<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Location;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\User;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(\Slides\Saml2\Events\SignedIn::class, function (\Slides\Saml2\Events\SignedIn $event) {
            $messageId = $event->getAuth()->getLastMessageId();

            // your own code preventing reuse of a $messageId to stop replay attacks
            $samlUser = $event->getSaml2User();
            $userData = [
                'id' => $samlUser->getUserId(),
                'attributes' => $samlUser->getAttributes(),
                'assertion' => $samlUser->getRawSamlAssertion()
            ];
            try {
                $user = User::where('email', $userData['id'])->first();
                $firstname = $samlUser->getAttribute('firstname');
                $lastname = $samlUser->getAttribute('lastname');
                $first = implode(" ", $firstname);
                $last = implode(" ", $lastname);
                $fullname = $first . ' ' . $last;

                $organization = str_replace("/","",$samlUser->getAttribute('category'));
                $organ = strtolower(str_replace(' ','',$organization[0]) ?? '');
                $locationCategory = explode('-', $organ);
                $category_id = null;
                $location_id = null;
                
                if (count($locationCategory) < 2) {
                    if ($organ == "Development Class") {
                        $category = Category::where('slug', 'dc')->first();
                        $category_id = $category->id;
                        // $category_id = $this->checkCategory($category, 'dc', 4);
                    }
                    $location = Location::where('name', 'Bintaro')->first();
                    $location_id = $this->checkLocation($location, "Bintaro");
                } else {
                    if ($locationCategory[1] == "staff") {
                        $category = Category::where('slug', 'general')->first();
                        $category_id = $category->id;
                        // $category_id = $this->checkCategory($category, 'general', 1);
                    } else if ($locationCategory[1] == "developmentclass"){
                        $category = Category::where('slug', 'dc')->first();
                        $category_id = $category->id;
                        // $category_id = $this->checkCategory($category, 'dc', 4);
                    } else {
                        $category = Category::where('slug', $locationCategory[1])->first();
                        $category_id = $category->id;
                        // $category_id = $this->checkCategory($category, $locationCategory[1], 1);
                    }
                    $location = Location::where('name', $locationCategory[0])->first();
                    $location_id = $this->checkLocation($location, $locationCategory[0]);
                }

                if (empty($user)) {
                    $user = User::create([
                        'full_name' => $fullname,
                        'email' => $userData['id'],
                        'role_id' => 1,
                        'category_id' => $category_id,
                        'location_id' => $location_id,
                        'organ_id' => 1047,
                        'role_name' => Role::$user,
                        'status' => User::$active,
                        'verified' => true,
                        'created_at' => time(),
                        'password' => null,
                        'avatar' => '/store/1047/avatar/6462fbd7cab78.png',
                        'avatar_settings' => '{"color":"000000","background":"f5f5f5"}'
                    ]);
                } else {
                    if ($user->category_id == null) {
                        $user->update([
                            'category_id' => $category_id,
                        ]);
                    }

                    if ($user->location_id == null) {
                        $user->update([
                            'location_id' => $location_id,
                        ]);
                    }
                }

                Auth::login($user);

                return redirect('/testing');
            } catch (Exception $e) {
                $toastData = [
                    'title' => trans('public.request_failed'),
                    'msg' => trans('auth.fail_login_by_facebook'),
                    'status' => 'error'
                ];
                return back()->with(['toast' => $toastData]);
            }
        });
    }

    public function checkCategory($category, $slug, $order)
    {
        if (isset($category)) {
            return $category->id;
        } else {
            $category = Category::create([
                'slug' => $slug,
                'parent_id' => null,
                'icon' => '/store/1/default_images/categories_icons/briefcase.png',
                'order' => $order
            ]);
            return $category->id;
        }
    }
    public function checkLocation($location, $name)
    {
        if (isset($location)) {
            return $location->id;
        } else {
            $location = Location::create([
                'name' => $name
            ]);
            return $location->id;
        }
    }
}
