<?php
declare(strict_types=1);

namespace Simonetoo\Dify\Apps;

use Simonetoo\Dify\Responses\Response;
use Simonetoo\Dify\Responses\StreamResponse;

class Dataset extends App
{

    /**
     * Summary of listDataset
     * @param int $page
     * @param int $limit
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function listDataset(int $page = 0, int $limit = 20): Response
    {
        return $this->client->get("datasets", ['page' => $page, 'limit' => $limit]);
    }
    /**
     * Summary of createDataset
     * @param string $name
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function createDataset(string $name): Response
    {
        return $this->client->postJson("datasets", [
            'name' => $name
        ]);
    }
    /**
     * Summary of deleteDataset
     * @param string $dataset_id
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function deleteDataset(string $dataset_id): Response
    {
        return $this->client->request("DELETE","datasets/{$dataset_id}");
    }
    /**
     * Summary of createDocumentByText
     * @param string $dataset_id
     * @param string $name
     * @param string $text
     * @param string $indexing_technique
     * @param array $process_rule
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function createDocumentByText(string $dataset_id, string $name, string $text, string $indexing_technique  = "high_quality", array $process_rule = []): Response
    {
        $process_rule = $this->getProcessRule($process_rule);
        return $this->client->postJson("datasets/{$dataset_id}/document/create_by_text", [
            'name' => $name,
            'text' => $text,
            'indexing_technique' => $indexing_technique,
            'process_rule' => $process_rule,
        ]);
    }
    /**
     * Summary of updateDocumentByText
     * @param string $dataset_id
     * @param string $document_id
     * @param string $name
     * @param string $text
     * @param array $process_rule
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function updateDocumentByText(string $dataset_id, string $document_id, string $name, string $text, array $process_rule = []): Response
    {
        $process_rule = $this->getProcessRule($process_rule);
        return $this->client->postJson("datasets/{$dataset_id}/documents/{$document_id}/update_by_text", [
            'name' => $name,
            'text' => $text,
            'process_rule' => $process_rule,
        ]);
    }
    /**
     * Summary of createDocumentByFile
     * @param string $dataset_id
     * @param string $filename
     * @param string $filePath
     * @param string $indexing_technique
     * @param array $process_rule
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function createDocumentByFile(string $dataset_id, string $filename, string $filePath, string $indexing_technique  = "high_quality", array $process_rule = []): Response
    {
        $process_rule = $this->getProcessRule($process_rule);
        $data = [
            'name' => $filename,
            'indexing_technique' => $indexing_technique,
            'process_rule' => $process_rule,
        ];
        return $this->client->request('POST', "datasets/{$dataset_id}/document/create_by_file", [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                ],
                [
                    'name' => 'data',
                    'contents' => json_encode($data)
                ]
            ]

        ]);
    }
    /**
     * Summary of updateDocumentByFile
     * @param string $dataset_id
     * @param string $document_id
     * @param string $filename
     * @param string $filePath
     * @param array $process_rule
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function updateDocumentByFile(string $dataset_id, string $document_id, string $filename, string $filePath, array $process_rule = []): Response
    {
        $process_rule = $this->getProcessRule($process_rule);
        $data = [
            'name' => $filename,
            'process_rule' => $process_rule,
        ];
        return $this->client->request('POST', "datasets/{$dataset_id}/documents/{$document_id}/update_by_file", [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filePath, 'r'),
                    'filename' => $filename
                ],
                [
                    'name' => 'data',
                    'contents' => $data
                ]
            ]

        ]);
    }
    /**
     * Summary of deleteDocument
     * @param string $dataset_id
     * @param string $document_id
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function deleteDocument(string $dataset_id, string $document_id): Response
    {
        return $this->client->request('DELETE',"datasets/{$dataset_id}/documents/{$document_id}");
    }
    /**
     * Summary of listDocument
     * @param string $dataset_id
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function listDocument(string $dataset_id, string $keyword, int $page = 0, int $limit = 20): Response
    {
        return $this->client->get("datasets/{$dataset_id}/documents", [
            "keyword" => $keyword,
            "page" => $page,
            "limit" => $limit,
        ]);
    }
    /**
     * Summary of getDocumentIndexStatus
     * @param string $dataset_id
     * @param string $batch
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function getDocumentIndexStatus(string $dataset_id, string $batch): Response
    {
        return $this->client->get("datasets/{$dataset_id}/documents/{$batch}/indexing-status");
    }
    /**
     * Summary of createDocumentSegments
     * @param string $dataset_id
     * @param string $document_id
     * @param array $segments
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function createDocumentSegments(string $dataset_id, string $document_id, array $segments = []): Response
    {
        return $this->client->postJson("datasets/{$dataset_id}/documents/{$document_id}/segments", [
            'segments' => $segments,
        ]);
    }
    /**
     * Summary of listDocumentSegments
     * @param string $dataset_id
     * @param string $document_id
     * @param string $keyword
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function listDocumentSegments(string $dataset_id, string $document_id, string $keyword = null): Response
    {
        return $this->client->get("datasets/{$dataset_id}/documents/{$document_id}/segments", [
            'keyword' => $keyword,
        ]);
    }
    /**
     * Summary of updateDocumentSegment
     * @param string $dataset_id
     * @param string $document_id
     * @param string $segment_id
     * @param array $segment
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function updateDocumentSegment(string $dataset_id, string $document_id, string $segment_id, array $segment): Response
    {
        return $this->client->postJson("datasets/{$dataset_id}/documents/{$document_id}/segments/{$segment_id}", [
            'segment' => $segment
        ]);
    }
    /**
     * Summary of deleteDocumentSegment
     * @param string $dataset_id
     * @param string $document_id
     * @param string $segment_id
     * @return \Simonetoo\Dify\Responses\Response
     */
    public function deleteDocumentSegment(string $dataset_id, string $document_id, string $segment_id): Response
    {
        return $this->client->request('DELETE',"datasets/{$dataset_id}/documents/{$document_id}/segments/{$segment_id}");
    }
    /**
     * Summary of getProcessRule
     * @param array $process_rule
     * @return array
     */
    public function getProcessRule(array $process_rule = []) {
        $process_rule = array_merge_recursive(
            [
                'mode' => "custom",
                'rules' => [
                    "pre_processing_rules" => [
                        [
                            "id" => "remove_extra_spaces",
                            "enabled" => true
                        ],
                        [
                            "id" => "remove_urls_emails",
                            "enabled" => true
                        ],
                    ],
                    "segmentation" => [
                        "separator" => "\n",
                        "max_tokens" => 500
                    ]
                ]
            ],
            $process_rule
        );
        return $process_rule;
    }
}
