<?php

namespace Qifen\WebmanApiResponse;

use support\Response;
use support\Container;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse {

    /**
     * @var int 状态码
     */
    protected $statusCode = 0;

    /**
     * @var array 头部
     */
    protected $header = ['Content-Type' => 'application/json'];

    protected function CodeAdapter(){
        return Container::get(config('plugin.qifen.webman-api.adapter'));
    }

    /**
     * 设置状态码
     *
     * @param int $statusCode
     * @return $this
     */
    protected function setStatusCode(int $statusCode = 0) {
        if($statusCode === 0) $statusCode == $this->CodeAdapter()::STATUS_OK;
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * 设置头部
     *
     * @param array $header
     * @return $this
     */
    protected function setHeader(array $header) {
        if (!empty($header)) {
            $this->header = array_merge($this->header, $header);
        }

        return $this;
    }

    /**
     * 响应
     *
     * @param array $data
     * @return Response
     */
    protected function response(array $data) {
        return new Response(200, $this->header, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 根据状态码组装数据
     *
     * @param array $data
     * @param string $msg
     * @return Response
     */
    protected function status(array $data = [], string $msg = '') {
        $code = $this->statusCode;

        $result = [
            'code' => $code,
            'msg' => Code::getStatusText($code, $msg),
            'data' => (object)$data,
        ];

        return $this->response($result);
    }

    /**
     * 根据状态码响应
     *
     * @param int $code
     * @param array $data
     * @param string $msg
     * @param array $header
     * @return Response
     */
    public function responseWithCode(int $code = 0, array $data = [], string $msg = '', array $header = []) {
        return $this->setHeader($header)->setStatusCode($code)->status($data, $msg);
    }

    /**
     * 成功响应
     *
     * @param array $data
     * @param string $msg
     * @param array $header
     * @return Response
     */
    public function success(array $data = [], string $msg = '', array $header = []) {
        return $this->responseWithCode(0, $data, $msg, $header);
    }

    /**
     * 根据状态码错误响应
     *
     * @param int $code
     * @param string $msg
     * @param array $data
     * @param array $header
     * @return Response
     */
    public function errorWithCode(int $code = 0, string $msg = '', array $data = [], array $header = []) {
        if($code === 0 ) $code = $this->CodeAdapter()::STATUS_ERROR;
        return $this->responseWithCode($code, $data, $msg, $header);
    }

    /**
     * 通用错误响应
     *
     * @param string $msg
     * @param array $data
     * @param array $header
     * @return Response
     */
    public function error(string $msg = '', array $data = [], array $header = []) {
        return $this->responseWithCode($this->CodeAdapter()::STATUS_ERROR, $data, $msg, $header);
    }

    /**
     * 参数错误响应
     *
     * @param string $msg
     * @param array $header
     * @return Response
     */
    public function errorParam(string $msg = '参数错误', array $header = []) {
        return $this->responseWithCode($this->CodeAdapter()::STATUS_ERROR_PARAM, [], $msg, $header);
    }

    /**
     * 成功或错误响应
     *
     * @param bool $success
     * @param array $data
     * @param int $errorCode
     * @return Response
     */
    public function successOrError(bool $success, array $data = [], int $errorCode = 0) {
        if ($success) return $this->success($data);
        if ($errorCode === 0) $errorCode = $this->CodeAdapter()::STATUS_ERROR;
        return $this->errorWithCode($errorCode);
    }

    /**
     * 分页响应
     *
     * @param LengthAwarePaginator $paginator
     * @return Response
     */
    public function page(LengthAwarePaginator $paginator) {
        return $this->success([
            'list' => $paginator->items(),
            'total' => $paginator->total(),
        ]);
    }
}