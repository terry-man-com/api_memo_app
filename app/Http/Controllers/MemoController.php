<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class MemoController extends Controller
{

    const HOST = 'https://memo-app-4.onrender.com';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 変数を用意
        $url = '/api/memos/';
        $method = 'GET';

        $memos = $this->getResponse($url, $method);
        return view('memos.index', compact('memos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('memos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 変数を用意
        $url = '/api/memos';
        $method = 'POST';

        $this->setResponse($request, $url, $method);
        return redirect('/memos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 変数を用意
        $url = '/api/memos/' . $id;
        $method = 'GET';

        $memo = $this->getResponse($url, $method);
        return view('memos.show', compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // 変数を用意
        $url = '/api/memos/' . $id;
        $method = 'GET';

        $memo = $this->getResponse($url, $method);
        return view('memos.edit', compact('memo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 変数を用意
        $url = '/api/memos/' . $id;
        $method = 'PUT';

        $this->setResponse($request, $url, $method);
        return redirect('/memos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 // 変数を用意
        $url = '/api/memos/' . $id;
        $method = 'DELETE';
        
        // 接続
        $client = new Client();
        try {
            $client->request($method, self::HOST . $url);
        } catch (\Exception $e) {
            return back();
}
        

        return redirect('/memos');
    }

    protected function getResponse($url, $method)
    {
        // Client(接続するためのクラス)を用意
        $client = new Client();
        // 接続失敗時はnullを返すようにする。
        try {
            $response = $client->request($method, self::HOST . $url);
        // $responseのBodyを取得
            $body  = $response->getBody();
            $data = json_decode($body, false);
        } catch (\Exception $e) {
            $data = null;
        }
        return $data;
    }

    protected function setResponse($request, $url, $method)
    {
        // 送信するデータとヘッダーを用意
        $memo = [
            'title' => $request->title,
            'body'  => $request->body,
        ];
        $options = [
            'json' => $memo,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ],
        ];

        // 接続
        $client = new Client(['http_errors' => false]);
        try {
            // URLにアクセスした結果を変数$responseに代入
            $response = $client->request($method, self::HOST . $url, $options);
            $body = $response->getBody();
            $json = json_decode($body, false);
            if (isset($json->errors)) {
                return back()->withErrors($json->errors);
            }
        } catch (\Exception $e) {
            return back();
        }
    }
}
