<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Authorization;
use App\Jobs\SendRegisterEmail;
use App\Transformers\UserTransformer;

class UserController extends BaseController
{
    /**
     * @api {get} /users user list
     * @apiDescription user list
     * @apiGroup user
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": [
     *         {
     *           "id": 2,
     *           "email": "smvorwerk2@gmail.com",
     *           "name": "Stephen2",
     *           "created_at": "2020-11-12 10:37:14",
     *           "updated_at": "2020-11-13 02:26:36",
     *           "deleted_at": null
     *         }
     *       ],
     *       "meta": {
     *         "pagination": {
     *           "total": 1,
     *           "count": 1,
     *           "per_page": 15,
     *           "current_page": 1,
     *           "total_pages": 1,
     *           "links": []
     *         }
     *       }
     *     }
     */
    public function index(User $user)
    {
        $users = User::paginate();

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * @api {put} /user/password edit password
     * @apiDescription edit password
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} old_password
     * @apiParam {String} password
     * @apiParam {String} password_confirmation
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "password": [
     *             "The passwords didn't match!",
     *             "The old and new passwords cannot be the same!"
     *         ],
     *         "password_confirmation": [
     *             "The passwords didn't match!"
     *         ],
     *         "old_password": [
     *             "Incorrect Password!"
     *         ]
     *     }
     */
    public function editPassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = $this->user();

        $auth = \Auth::once([
            'email' => $user->email,
            'password' => $request->get('old_password'),
        ]);

        if (! $auth) {
            return $this->response->errorUnauthorized();
        }

        $password = app('hash')->make($request->get('password'));
        $user->update(['password' => $password]);

        return $this->response->noContent();
    }

    /**
     * @api {get} /users/{id} user's info
     * @apiDescription user's info
     * @apiGroup user
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 2,
     *         "email": "smvorwerk2@gmail.com",
     *         "name": "Stephen2",
     *         "created_at": "2020-11-12 10:37:14",
     *         "updated_at": "2020-11-13 02:26:36",
     *         "deleted_at": null
     *       }
     *     }
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @api {get} /user current user info
     * @apiDescription current user info
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "data": {
     *         "id": 2,
     *         "email": 'smvorwerk2@gmail.com',
     *         "name": "Stephen2",
     *         "created_at": "2020-09-08 09:13:57",
     *         "updated_at": "2020-09-08 09:13:57",
     *         "deleted_at": null
     *       }
     *     }
     */
    public function userShow()
    {
        return $this->response->item($this->user(), new UserTransformer());
    }

    /**
     * @api {patch} /user update info
     * @apiDescription update info
     * @apiGroup user
     * @apiPermission JWT
     * @apiVersion 0.1.0
     * @apiParam {String} [name] name
     * @apiParam {Url} [avatar] avatar
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *        "id": 1,
     *        "email": 'smvorwerk@gmail.com',
     *        "name": "Stephen",
     *        "created_at": "2020-10-28 07:30:56",
     *        "updated_at": "2020-10-28 09:42:43",
     *        "deleted_at": null,
     *     }
     */
    public function patch(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'name' => 'string|max:50',
            'avatar' => 'url',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $user = $this->user();
        $attributes = array_filter($request->only('name', 'avatar'));

        if ($attributes) {
            $user->update($attributes);
        }

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * @api {post} /users create a new user
     * @apiDescription create a new user
     * @apiGroup user
     * @apiPermission none
     * @apiVersion 0.1.0
     * @apiParam {Email}  email         email[unique]
     * @apiParam {String} password      password
     * @apiParam {String} name          name
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         token: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL21vYmlsZS5kZWZhcmEuY29tXC9hdXRoXC90b2tlbiIsImlhdCI6IjE0NDU0MjY0MTAiLCJleHAiOiIxNDQ1NjQyNDIxIiwibmJmIjoiMTQ0NTQyNjQyMSIsImp0aSI6Ijk3OTRjMTljYTk1NTdkNDQyYzBiMzk0ZjI2N2QzMTMxIn0.9UPMTxo3_PudxTWldsf4ag0PHq1rK8yO9e5vqdwRZLY
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "email": [
     *             "This email has already been registered!"
     *         ],
     *     }
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'email' => 'required|email|unique:users',
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $email = $request->get('email');
        $password = $request->get('password');

        $attributes = [
            'email' => $email,
            'name' => $request->get('name'),
            'password' => app('hash')->make($password),
        ];
        $user = User::create($attributes);

        // Send an email after the user has successfully registered
        dispatch(new SendRegisterEmail($user));

        // 201 with location
        $location = dingo_route('v1', 'users.show', $user->id);

        return $this->response->item($user, new UserTransformer())
            ->header('Location', $location)
            ->setStatusCode(201);
    }
}
