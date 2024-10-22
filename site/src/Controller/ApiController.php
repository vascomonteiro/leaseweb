<?php

namespace App\Controller;

use App\Service\DataExtractor;
use App\Service\DataFilter;
use App\Validator\FileValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(private DataExtractor $dataExtractor, private DataFilter $dataFilter, private FileValidator $fileValidator){}

    #[Route('/api/servers', name: 'api_get_servers', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $filePath = $this->getParameter('uploads_directory') . '/data.xlsx';

        // Validate the file
        $this->fileValidator->validateFileExists($filePath);

        // Extract data from the file
        $data = $this->dataExtractor->extractData($filePath);
        $rows = $data['rows'];
        $locationList = $data['locations'];
        $ramList = $data['ramList'];
        $minHDDCapacity = $data['minHDDCapacity'];
        $maxHDDCapacity = $data['maxHDDCapacity'];

        // Get filter parameters
        $filterRam = explode(',', $request->query->get('ram', ''));
        $filterLocation = $request->query->get('location', '');
        $filterHddType = $request->query->get('hdd', '');

        // Handle range slider for HDD capacity
        if ($request->query->get('rangeSlider') !== null) {
            $rangeHDDCapacity = explode(';', $request->query->get('rangeSlider'));
            $minHDDCapacity = $rangeHDDCapacity[0];
            $maxHDDCapacity = $rangeHDDCapacity[1];
        }

        // Filter the data
        $filteredItems = $this->dataFilter->filterData($rows, $filterRam, $filterLocation, $filterHddType, $minHDDCapacity, $maxHDDCapacity);

        // Return the response
        return new JsonResponse([
            'data' => $filteredItems,
            'location' => $locationList,
            'rangeHDDCapacity' => [$minHDDCapacity, $maxHDDCapacity],
            'msg' => 'OK',
            'ram' => $ramList,
        ]);
    }
}
