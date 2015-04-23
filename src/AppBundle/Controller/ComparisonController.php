<?php

namespace AppBundle\Controller;

use AppBundle\Utils\ComparisonUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RouteComparisonController.
 *
 * @Route("/compare")
 */
class ComparisonController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $grand_var = 'Sequence No. |     1      |     2      |     3      |     4      |     5      |     6      |
Flight No.   |     EY0001 |     EY0001 |     EY0001 |     EY0001 |     EY0001 |     EY0001 |
Dep-Dest     |    ALA-AUH |    DME-AUH |    TBS-AUH |    GYD-AUH |    EVN-AUH |    MSQ-AUH |
Route        |     MCT    |     MCT    |     MCT    |     MCT    |     MCT    |     MCT    |
ACFT         |   A6EIV    |   A6EIV    |   A6EIV    |   A6EIV    |   A6EIV    |   A6EIV    |
Season       |     ANU/80 |     ANU/80 |     ANU/80 |     ANU/80 |     ANU/80 |     ANU/80 |
Awy Dist NM  |      1756  |      2083  |      1248  |      1045  |      1136  |      2276  |
Avg WC kts   |      -49   |      -10   |       -6   |      -14   |       -4   |      -10   |
NAM          |     1977   |     2131   |     1266   |     1080   |     1147   |     2328   |
TAS kts      |      449   |      449   |      449   |      449   |      449   |      449   |
Cruise Proc  |   CI  15   |   CI  15   |   CI  15   |   CI  15   |   CI  15   |   CI  15   |
             |            |            |            |            |            |            |
Trip Time    |     04:32  |     04:55  |     03:00  |     02:35  |     02:44  |     05:20  |
Taxi Time    |     00:22  |     00:25  |     00:15  |     00:15  |     00:20  |     00:33  |
Block Time   |     04:54  |     05:20  |     03:15  |     02:50  |     03:04  |     05:53  |
             |            |            |            |            |            |            |
Trip Fuel    |  10675 kg  |  11593 kg  |   6902 kg  |   5985 kg  |   6268 kg  |  12646 kg  |
Block Fuel   |  10969 kg  |  11927 kg  |   7102 kg  |   6185 kg  |   6535 kg  |  13086 kg  |
PLNTOF       |  12773 kg  |  13737 kg  |   8866 kg  |   7949 kg  |   8232 kg  |  14828 kg  |
Tanks Fuel   |  13000 kg  |  14004 kg  |   8999 kg  |   8082 kg  |   8432 kg  |  15068 kg  |
CONT         |    534 kg  |    580 kg  |    400 kg  |    400 kg  |    400 kg  |    632 kg  |
CONT FUEL POL|   CONT5%   |   CONT5%   |   CONTMIN  |   CONTMIN  |   CONTMIN  |   CONT5%   |
ALTN RTE     |     ???    |     ???    |     ???    |     ???    |     ???    |     ???    |
ALTN/DIST NM |       /    |       /    |       /    |       /    |       /    |       /    |
ALTN Fuel    |            |            |            |            |            |            |
Holding      |   1564 kg  |   1564 kg  |   1564 kg  |   1564 kg  |   1564 kg  |   1550 kg  |
             |            |            |            |            |            |            |
Tot.Reserve  |   2098 kg  |   2144 kg  |   1964 kg  |   1964 kg  |   1964 kg  |   2182 kg  |
TCAP         |  18730 kg  |  18730 kg  |  18730 kg  |  18730 kg  |  18730 kg  |  18730 kg  |
Pos.Extra    |   5216 kg  |   4290 kg  |   5350 kg  |   5350 kg  |   5350 kg  |   3212 kg  |
Extra F.Prio |      1276C |       872C |      4877C |      5717C |      5395C |       647C |
             |            |            |            |            |            |            |
Dep Rwy      |       05L  |       32L  |       31L  |        18  |        09  |        31  |
Dest Rwy     |       31L  |       31L  |       31L  |       31L  |       31L  |        31  |
MALTOW       |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |
MTOW         |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |  77000 kg  |
PLNTOW       |  71459 kg  |  72423 kg  |  67552 kg  |  66635 kg  |  66918 kg  |  73528 kg  |
MALLW        |  66000 kg  |  66000 kg  |  66000 kg  |  66000 kg  |  66000 kg  |  66000 kg  |
PLNLW        |  60784 kg  |  60830 kg  |  60650 kg  |  60650 kg  |  60650 kg  |  60882 kg  |
MAXZFW       |  62500 kg  |  62500 kg  |  62500 kg  |  62500 kg  |  62500 kg  |  62500 kg  |
PLNZFW       |  58250 kg  |  58250 kg  |  58250 kg  |  58250 kg  |  58250 kg  |  58250 kg  |
DOW          |  45500 kg  |  45500 kg  |  45500 kg  |  45500 kg  |  45500 kg  |  45500 kg  |
Load         |  12750 kg* |  12750 kg* |  12750 kg* |  12750 kg* |  12750 kg* |  12750 kg* |
Load %       |        75  |        75  |        75  |        75  |        75  |        75  |
             |            |            |            |            |            |            |
Total Costs  |      18252 |      15420 |      10529 |       7712 |      13301 |      25541 |
ATC Charges  |       1552 |       2665 |       1558 |        964 |       1301 |       2791 |
Time Costs   |       6147 |       6672 |       4067 |       3512 |       3714 |       7235 |
Curr/Date    |   USD210415|   USD210415|   USD210415|   USD210415|   USD210415|   USD210415|
             |            |            |            |            |            |            |
             |            |            |            |            |            |            |
Run-Date     |   20.04.15 |   20.04.15 |   20.04.15 |   20.04.15 |   20.04.15 |   20.04.15 |';

        $platoArray = [];

        $form = $this->createFormBuilder(array('plato' => 'Copy PLATO text here'))
            ->add('plato', 'textarea')
            ->add('Parse', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $utils = new ComparisonUtils();
            $platoArray = $utils->platoToArray($data['plato']);
        }

        return $this->render('AppBundle:Comparison:index.html.twig', array(
            'form' => $form->createView(),
            'plato_info' => $platoArray,
        ));
    }
}
