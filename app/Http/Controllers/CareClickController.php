<?php

namespace App\Http\Controllers;
use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;

class CareClickController extends Controller
{
    public function login(Request $request){
            $email = $request->email;
            $password = $request->password;
          
        
        $client = new GuzzleClient(['http_errors' => false]);
        $header= [
            "Content-Type"=>"application/json"
        ];
        $body = [
            "email" => $email,
            "password" => $password
        ];
        
        $body=json_encode($body);
        

        //make request
        $params = [
            "headers" => $header,
            "body" => $body
        ];
        $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/login', $params);

        // Get Response
        $response = json_decode($request->getBody());

        // Return our custom response
        return (array) $response;
    }

    //register

    public function register(Request $request){
        
        
        $client = new GuzzleClient(['http_errors' => false]);
        $header= [
            "Content-Type"=>"application/json"
        ];
        $body = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => $request->password,
            'click_id' => $request->click_id
        ];
        $body=json_encode($body); 
        //make request
        $params = [
            "headers" => $header,
            "body" => $body
        ];
        $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/register-patient
        ', $params);

        // Get Response
        $response = json_decode($request->getBody());

        // Return our custom response
        return (array) $response;
        return $request;
    }
//GET PATIENT OBJECT
    public function getPatientObject(Request $request){
        $client = new GuzzleClient(['http_errors' => false]);
        $params = [
            'query' => ['token' => $request->token]
        ];
        $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient', $params);

        // Get Response
        $response = json_decode($request->getBody());

         // Return our custom response
        return (array) $response;
    }
//ADD QUESTIONS

    public function AddQuestion(Request $request){
        
        
        $client = new GuzzleClient(['http_errors' => false]);

        $header = [
            "Content-Type" => "application/json"
        ];
        $body= [
            "data" =>[
            'question' => $request->question,
            'answer' => $request->answer
            ]
    
        ];
        $body=json_encode($body);
        
        $query = ["token" => $request->token
                    
    ];

        // Make Request
        $params = [
            "headers" => $header,
            "body"=>$body,
            "query" => $query
        ];
        $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/add-question', $params);
        // Get Response
        $response = json_decode($request->getBody());

        // Return our custom response
        return (array) $response;
    }

//FETCH APPOINTMENT

    public function FetchAppointment(Request $request){
        $client = new GuzzleClient(['http_errors' => false]);
        $params = [
            'query' => ['token' => $request->token,
            'appointment_id' => $request->appointment_id
        ],
            
        ];
        $request = $client->request('GET','https://careclickapi.com/api/v1/patient/history/appointments/fetch-appointment
        ', $params);

        // Get Response
        $response = json_decode($request->getBody());

        // Return our custom response
        return (array) $response;
    } 

//Appointment history

    public function AppointmentHistory(Request $request){
        $client = new GuzzleClient(['http_errors' => false]);
        $params = [
            'query' => ['token' => $request->token,
            'null'=> $request->null
        ],
            
    ];
        $request = $client->request('GET','https://careclickapi.com/api/v1/patient/history/appointments
        ', $params);

        // Get Response
        $response = json_decode($request->getBody());

        // Return our custom response
        return (array) $response;
    } 

    //FETCH ORDER


public function FetchOrder(Request $request){
    $client = new GuzzleClient(['http_errors' => false]);
    $params = [
        'query' => ['token' => $request->token,
        'order_id' => $request->order_id
    ],
        
    ];
    $request = $client->request('GET','https://careclickapi.com/api/v1/patient/history/orders/fetch-order
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
} 

//order history

public function OrderHistory(Request $request){
    $client = new GuzzleClient(['http_errors' => false]);
    $params = [
        'query' => ['token' => $request->token
    ]
        
    ];
    $request = $client->request('GET','https://careclickapi.com/api/v1/patient/history/orders
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
} 

// search provider

public function SearchProvider(Request $request){
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["token" => $request->token,
    "name" => $request->name
    ];
    // Make Request
    $params = [
        "headers" => $header,
        "query" => $query
    ];
    $request = $client->request('GET','https://careclickapi.com/api/v1/patient/search-providers
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
} 

//FETCH PROVIDER

public function FetchProvider(Request $request){
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
       
    $query = ["token" => $request->token];

    $body =[
        'provider_id'=>$request->provider_id];
    $body=json_encode($body);
    
    

    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/provider/fetch-provider', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;

}



 //FETCH PROVIDERS******* 
 public function FetchProviders(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["token"=>$request->token];
    $query = ["token" => $request->token];

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query
    ];
    
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-providers',$params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH Plans
public function FetchPlans(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);


    //make request
    $params = [
        
        "query"=>$query,
        "headers"=>$header,
        "body"=>$body
    ];
    
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-plans',$params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

///subscribe

public function Subscribe(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/subscribe', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}



public function FetchSubscribe(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["token" => $request->token];
    $query = ["token" => $request->token];

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query
    ];
    
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-providers',$params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//CHECKOUTplans

public function CheckOutPlans(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    

    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/checkout-plan
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//CHECKOUt

public function CheckOut(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/checkout
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//add coupon

public function AddCoupon(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $body= $request->body;
    $query = [
        'token' => $request->token
    ];

    //make request
    $params = [
        "query" => $query,
        "headers" => $header,
        "body" => $body
    ];
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/cart/add-coupon
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//REMOVE COUPON

public function RemoveCoupon(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/cart/remove

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//DECREASE PRODUCT
public function DecreaseProduct(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/cart/decrease
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//INCREASE PRODUCT
public function IncreaseProduct(Request $request){  
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/cart/increase

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//ADD TO CART
public function AddToCart(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= [
        "Content-Type"=>"application/json"
    ];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
        "headers" => $header,
        "body" => $body,
        "query"=>$query
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/cart/add
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH CART PRODUCT

public function FetchCartProduct(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query  
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/cart
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH Brand

public function FetchBrand(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token];

    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-brands
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//FETCH categories

public function FetchCategories(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token];

    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-categories
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FILTER PRODUCT

public function FilterProducts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'minprice'=>$request->minprice,
    'maxprice'=>$request->maxprice,
    'brandid'=>$request->brandid,
    'categoryid'=>$request->categoryid
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/filter-products
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH PRODUCT

public function FetchProduct(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'product_id'=>$request->product_id
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-product
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//SERCH PRODUCT

public function SearchProducts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'product_name'=>$request->product_name
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/search-products
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH PRODUCTS

public function FetchProducts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'null'=>$request->null
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-products
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH FAVORITE POSTS

public function FetchFavoritePosts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-favorite-posts
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//TOGGLE POST FAVORITE

public function TogglePostFavorite(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/toggle-post-favorite
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//TOGGLE POST LIKE

public function TogglePosLike(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/toggle-post-like
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//REPLY POST COMMENT

public function ReplyPostComment(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/reply-post-comment
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

// ADD POST COMMENT

public function AddPostComment(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/add-post-comment
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// FETCH POST COMMENT

public function FetchPostComments(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-post-comments
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

// FETCH CATEGORY

public function FetchCategory(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $query = ["token" => $request->token];
    //make request
    $params = [
        "query"=>$query
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-post-categories
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//SEARCH POSTS

public function SearchPosts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'topic'=>$request->topic
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/search-posts
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//FILTER POSTS

public function FilterPosts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'topic'=>$request->topic,
    'categoryid'=>$request->categoryid
];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/filter-posts
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH POST

public function FetchPost(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    'post_id'=>$request->post_id
    ];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-post
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH POSTS

public function FetchPosts(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token,
    ];
    //make request
    $params = [
       
        "query"=>$query  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-posts
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

// NOTIFY PROVIDER

public function NotifyProvider(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/notify-provider

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// FETCH NOTIFICATIONs

public function FetchNotifications(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);

    $query = ["token" => $request->token];

    //make request
    $params = [
        "query"=>$query
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-notifications

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

// CHANGE PROFILE DETAILS

public function ChangeProfileDetails(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('PATCH', 'https://careclickapi.com/api/v1/patient/change-profile-details

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// CHANGE PASSWORD

public function ChangePassword(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/update-password

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// UPDATE PROFILE IMAGE  (check this late)*******

public function UpdateProfileimage(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"multipart/form-data"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/update-profile-image

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// UPDATE PROFILE 

public function UpdateProfile(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["Content-Type"=>"application/json"];
    $query = ["token" => $request->token];
    $body = $request->body;
    $body=json_encode($body);

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
        "body"=>$body  
    ];

    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/update-profile

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

// GET COUNTRIES 

public function GetCountries(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header= ["token" => $request->token];
    $query = ["token" => $request->token];
    

    //make request
    $params = [
        "headers" => $header,
        "query"=>$query,
          
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/countries

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// GET STATE 

public function GetStates(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $query = ["country_id" => $request->country_id];
    

    //make request
    $params = [
        "query"=>$query
          
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/states

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
// GET CITIES 

public function GetCities(Request $request){
    
    
    $client = new GuzzleClient(['http_errors' => false]);
    $query = ["state_id" => $request->state_id];
    

    //make request
    $params = [
        "query"=>$query
          
    ];

    $request = $client->request('GET', 'https://careclickapi.com/api/v1/cities

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//verified code

public function VerifyCode(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["code" => $request->code,
    'email'=>$request->email
];
$body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/verify-code
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//RESEND CODE

public function ResendCode(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["null" => $request->null
];
$body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/resend-code
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}
//RESEND OTP

public function ResendOtp(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["null" => $request->null
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/resend-otp
    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FORGOT PASSWORD WEB

public function ForgotPasswordWeb(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["Email" => $request->Email
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/forgot-password-web

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FORGOT PASSWORD 

public function ForgotPassword(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["Email" => $request->Email
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/forgot-password

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//verify users 

public function VerifyUsers(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/verify-user


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//CREATE PASSWORD 

public function CreatePassword(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["Email" => $request->Email,
    'password'=>$request->Password,
    'password_confirmation'=>$request->password_confirmation
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/create-password

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//RESET PASSWORD 

public function ResetPassword(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["email" => $request->Email,
    'code'=>$request->code,
    'password'=>$request->Password,
    'password_confirmation'=>$request->password_confirmation
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/auth/reset-password

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//AUTH USERS 

public function AuthUsers(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token,
    'null'=>$request->null,
    
];

    //make request
    $params = [
      
        "query"=>$query
  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/auth/user

    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//LOGOUT 

public function LogOut(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token    
    
];

    //make request
    $params = [
      
        "query"=>$query
  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/auth/logout


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//ADD REVIEW 

public function AddReview(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["token" => $request->token
    
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/add-doctor-review


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


//Fetch DOCTORS REVIEW 

public function FetchDoctorReview(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token,
    'provider_id'=>$request->provider_id
    
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [

        "query"=>$query
         
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-provider-reviews


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FILTER APPOINTMENTS 

public function FilterAppointment(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token,
    'status'=>$request->status
    
];
    
    //make request
    $params = [

        "query"=>$query
         
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/filter-appointments


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//FETCH APPOINTMENTS 

public function FetchAppointments(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    
    $query = ["token" => $request->token
    
];
    
    //make request
    $params = [

        "query"=>$query
         
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/fetch-appointments


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//MARK AS COMPLETE 

public function MarkComplete(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["token" => $request->token
    
];
    $body = $request->body;
    $body=json_encode($body);
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
        "body"=>$body  
    ];
    
    $request = $client->request('POST', 'https://careclickapi.com/api/v1/patient/appointment-completed


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}

//CHECK URGENT APPOINTMENT 

public function CheckUrgentAppointment(Request $request){
    
    $client = new GuzzleClient(['http_errors' => false]);
    $header = [
        "Content-Type" => "application/json"
    ];
    $query = ["token" => $request->token
    
];
    
    //make request
    $params = [
       "header"=>$header,
        "query"=>$query,
  
    ];
    
    $request = $client->request('GET', 'https://careclickapi.com/api/v1/patient/check-urgent-appointment


    ', $params);

    // Get Response
    $response = json_decode($request->getBody());

    // Return our custom response
    return (array) $response;
}


}
?>
    