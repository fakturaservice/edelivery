<?php

namespace Fakturaservice\Edelivery\OIOUBL;
/**
 * Document name code (UNCL1001)
 *
 * Identifier:      UNCL1001
 * Agency:          UN/CEFACT
 * Version:         D.17A
 * Usage:
 *                  | ubl:Catalogue | cac:ReferencedContract | cbc:ContractTypeCode
 *                  | ubl:Catalogue | cac:CatalogueLine | cac:Item | cac:ItemSpecificationDocumentReference | cbc:DocumentTypeCode
 */
abstract class UNCL1001
{
    /**
     * Certificate of analysis.
     * Certificate providing the values of an analysis.
     */
    const _1 = "1";

    /**
     * Certificate of conformity.
     * Certificate certifying the conformity to predefined definitions.
     */
    const _2 = "2";

    /**
     * Certificate of quality.
     * Certificate certifying the quality of goods, services etc.
     */
    const _3 = "3";

    /**
     * Test report.
     * Report providing the results of a test session.
     */
    const _4 = "4";

    /**
     * Product performance report.
     * Report specifying the performance values of products.
     */
    const _5 = "5";

    /**
     * Product specification report.
     * Report providing specification values of products.
     */
    const _6 = "6";

    /**
     * Process data report.
     * Reports on events during production process.
     */
    const _7 = "7";

    /**
     * First sample test report.
     * Document/message describes the test report of the first sample.
     */
    const _8 = "8";

    /**
     * Price/sales catalogue.
     * A document/message to enable the transmission of information regarding pricing and catalogue details for goods and services offered by a seller to a buyer.
     */
    const _9 = "9";

    /**
     * Party information.
     * Document/message providing basic data concerning a party.
     */
    const _10 = "10";

    /**
     * Federal label approval.
     * A pre-approved document relating to federal label approval requirements.
     */
    const _11 = "11";

    /**
     * Mill certificate.
     * Certificate certifying a specific quality of agricultural products.
     */
    const _12 = "12";

    /**
     * Post receipt.
     * Document/message which evidences the transport of goods by post (e.g. mail, parcel, etc.).
     */
    const _13 = "13";

    /**
     * Weight certificate.
     * Certificate certifying the weight of goods.
     */
    const _14 = "14";

    /**
     * Weight list.
     * Document/message specifying the weight of goods.
     */
    const _15 = "15";

    /**
     * Certificate.
     * Document by means of which the documentary credit applicant specifies the conditions for the certificate and by whom the certificate is to be issued.
     */
    const _16 = "16";

    /**
     * Combined certificate of value and origin.
     * Document identifying goods in which the issuing authority expressly certifies that the goods originate in a specific country or part of, or group of countries. It also states the price and/or cost of the goods with the purpose of determining the customs origin.
     */
    const _17 = "17";

    /**
     * Movement certificate A.TR.1.
     * Specific form of transit declaration issued by the exporter (movement certificate).
     */
    const _18 = "18";

    /**
     * Certificate of quantity.
     * Certificate certifying the quantity of goods, services etc.
     */
    const _19 = "19";

    /**
     * Quality data message.
     * Usage of QALITY-message.
     */
    const _20 = "20";

    /**
     * Query.
     * Request information based on defined criteria.
     */
    const _21 = "21";

    /**
     * Response to query.
     * Document/message returned as an answer to a question.
     */
    const _22 = "22";

    /**
     * Status information.
     * Information regarding the status of a related message.
     */
    const _23 = "23";

    /**
     * Restow.
     * Message/document identifying containers that have been unloaded and then reloaded onto the same means of transport.
     */
    const _24 = "24";

    /**
     * Container discharge list.
     * Message/document itemising containers to be discharged from vessel.
     */
    const _25 = "25";

    /**
     * Corporate superannuation contributions advice.
     * Document/message providing contributions advice used for corporate superannuation schemes.
     */
    const _26 = "26";

    /**
     * Industry superannuation contributions advice.
     * Document/message providing contributions advice used for superannuation schemes which are industry wide.
     */
    const _27 = "27";

    /**
     * Corporate superannuation member maintenance message.
     * Member maintenance message used for corporate superannuation schemes.
     */
    const _28 = "28";

    /**
     * Industry superannuation member maintenance message.
     * Member maintenance message used for industry wide superannuation schemes.
     */
    const _29 = "29";

    /**
     * Life insurance payroll deductions advice.
     * Payroll deductions advice used in the life insurance industry.
     */
    const _30 = "30";

    /**
     * Underbond request.
     * A Message/document requesting to move cargo from one Customs control point to another.
     */
    const _31 = "31";

    /**
     * Underbond approval.
     * A message/document issuing Customs approval to move cargo from one Customs control point to another.
     */
    const _32 = "32";

    /**
     * Certificate of sealing of export meat lockers.
     * Document / message issued by the authority in the exporting country evidencing the sealing of export meat lockers.
     */
    const _33 = "33";

    /**
     * Cargo status.
     * Message identifying the status of cargo.
     */
    const _34 = "34";

    /**
     * Inventory report.
     * A message specifying information relating to held inventories.
     */
    const _35 = "35";

    /**
     * Identity card.
     * Official document to identify a person.
     */
    const _36 = "36";

    /**
     * Response to a trade statistics message.
     * Document/message in which the competent national authorities provide a declarant with an acceptance or a rejection about a received declaration for European statistical purposes.
     */
    const _37 = "37";

    /**
     * Vaccination certificate.
     * Official document proving immunisation against certain diseases.
     */
    const _38 = "38";

    /**
     * Passport.
     * An official document giving permission to travel in foreign countries.
     */
    const _39 = "39";

    /**
     * Driving licence (national).
     * An official document giving permission to drive a car in a given country.
     */
    const _40 = "40";

    /**
     * Driving licence (international).
     * An official document giving a native of one country permission to drive a vehicle in certain other countries.
     */
    const _41 = "41";

    /**
     * Free pass.
     * A document giving free access to a service.
     */
    const _42 = "42";

    /**
     * Season ticket.
     * A document giving access to a service for a determined period of time.
     */
    const _43 = "43";

    /**
     * Transport status report.
     * (1125) A message to report the transport status and/or change in the transport status (i.e. event) between agreed parties.
     */
    const _44 = "44";

    /**
     * Transport status request.
     * (1127) A message to request a transport status report (e.g. through the national multimodal status report message IFSTA).
     */
    const _45 = "45";

    /**
     * Banking status.
     * A banking status document and/or message.
     */
    const _46 = "46";

    /**
     * Extra-Community trade statistical declaration.
     * Document/message in which a declarant provides information about extra-Community trade of goods required by the body responsible for the collection of trade statistics. Trade by a country in the European Union with a country outside the European Union.
     */
    const _47 = "47";

    /**
     * Written instructions in conformance with ADR article number.
     * 10385 Written instructions relating to dangerous goods and defined in the European Agreement of Dangerous Transport by Road known as ADR (Accord europeen relatif au transport international des marchandises Dangereuses par Route).
     */
    const _48 = "48";

    /**
     * Damage certification.
     * Official certification that damages to the goods to be transported have been discovered.
     */
    const _49 = "49";

    /**
     * Validated priced tender.
     * A validated priced tender.
     */
    const _50 = "50";

    /**
     * Price/sales catalogue response.
     * A document providing a response to a previously sent price/sales catalogue.
     */
    const _51 = "51";

    /**
     * Price negotiation result.
     * A document providing the result of price negotiations.
     */
    const _52 = "52";

    /**
     * Safety and hazard data sheet.
     * Document or message to supply advice on a dangerous or hazardous material to industrial customers so as to enable them to take measures to protect their employees and the environment from any potential harmful effects from these material.
     */
    const _53 = "53";

    /**
     * Legal statement of an account.
     * A statement of an account containing the booked items as in the ledger of the account servicing financial institution.
     */
    const _54 = "54";

    /**
     * Listing statement of an account.
     * A statement from the account servicing financial institution containing items pending to be booked.
     */
    const _55 = "55";

    /**
     * Closing statement of an account.
     * Last statement of a period containing the interest calculation and the final balance of the last entry date.
     */
    const _56 = "56";

    /**
     * Transport equipment on-hire report.
     * Report on the movement of containers or other items of transport equipment to record physical movement activity and establish the beginning of a rental period.
     */
    const _57 = "57";

    /**
     * Transport equipment off-hire report.
     * Report on the movement of containers or other items of transport equipment to record physical movement activity and establish the end of a rental period.
     */
    const _58 = "58";

    /**
     * Treatment - nil outturn.
     * No shortage, surplus or damaged outturn resulting from container vessel unpacking.
     */
    const _59 = "59";

    /**
     * Treatment - time-up underbond.
     * Movement type indicator: goods are moved under customs control for warehousing due to being time-up.
     */
    const _60 = "60";

    /**
     * Treatment - underbond by sea.
     * Movement type indicator: goods are to move by sea under customs control to a customs office where formalities will be completed.
     */
    const _61 = "61";

    /**
     * Treatment - personal effect.
     * Cargo consists of personal effects.
     */
    const _62 = "62";

    /**
     * Treatment - timber.
     * Cargo consists of timber.
     */
    const _63 = "63";

    /**
     * Preliminary credit assessment.
     * Document/message issued either by a factor to indicate his preliminary credit assessment on a buyer, or by a seller to request a factor's preliminary credit assessment on a buyer.
     */
    const _64 = "64";

    /**
     * Credit cover.
     * Document/message issued either by a factor to give a credit cover on a buyer, or by a seller to request a factor's credit cover.
     */
    const _65 = "65";

    /**
     * Current account.
     * Document/message issued by a factor to indicate the money movements of a seller's or another factor's account with him.
     */
    const _66 = "66";

    /**
     * Commercial dispute.
     * Document/message issued by a party (usually the buyer) to indicate that one or more invoices or one or more credit notes are disputed for payment.
     */
    const _67 = "67";

    /**
     * Chargeback.
     * Document/message issued by a factor to a seller or to another factor to indicate that the rest of the amounts of one or more invoices uncollectable from buyers are charged back to clear the invoice(s) off the ledger.
     */
    const _68 = "68";

    /**
     * Reassignment.
     * Document/message issued by a factor to a seller or to another factor to reassign an invoice or credit note previously assigned to him.
     */
    const _69 = "69";

    /**
     * Collateral account.
     * Document message issued by a factor to indicate the movements of invoices, credit notes and payments of a seller's account.
     */
    const _70 = "70";

    /**
     * Request for payment.
     * Document/message issued by a creditor to a debtor to request payment of one or more invoices past due.
     */
    const _71 = "71";

    /**
     * Unship permit.
     * A message or document issuing permission to unship cargo.
     */
    const _72 = "72";

    /**
     * Statistical definitions.
     * Transmission of one or more statistical definitions.
     */
    const _73 = "73";

    /**
     * Statistical data.
     * Transmission of one or more items of data or data sets.
     */
    const _74 = "74";

    /**
     * Request for statistical data.
     * Request for one or more items or data sets of statistical data.
     */
    const _75 = "75";

    /**
     * Call-off delivery.
     * Document/message to provide split quantities and delivery dates referring to a previous delivery instruction.
     */
    const _76 = "76";

    /**
     * Consignment status report.
     * Message covers information about the consignment status.
     */
    const _77 = "77";

    /**
     * Inventory movement advice.
     * Advice of inventory movements.
     */
    const _78 = "78";

    /**
     * Inventory status advice.
     * Advice of stock on hand.
     */
    const _79 = "79";

    /**
     * Debit note related to goods or services.
     * Debit information related to a transaction for goods or services to the relevant party.
     */
    const _80 = "80";

    /**
     * Credit note related to goods or services.
     * Document message used to provide credit information related to a transaction for goods or services to the relevant party.
     */
    const _81 = "81";

    /**
     * Metered services invoice.
     * Document/message claiming payment for the supply of metered services (e.g., gas, electricity, etc.) supplied to a fixed meter whose consumption is measured over a period of time.
     */
    const _82 = "82";

    /**
     * Credit note related to financial adjustments.
     * Document message for providing credit information related to financial adjustments to the relevant party, e.g., bonuses.
     */
    const _83 = "83";

    /**
     * Debit note related to financial adjustments.
     * Document/message for providing debit information related to financial adjustments to the relevant party.
     */
    const _84 = "84";

    /**
     * Customs manifest.
     * Message/document identifying a customs manifest. The document itemises a list of cargo prepared by shipping companies from bills of landing and presented to customs for formal report of cargo.
     */
    const _85 = "85";

    /**
     * Vessel unpack report.
     * A document code to indicate that the message being transmitted identifies all short and surplus cargoes off-loaded from a vessel at a specified discharging port.
     */
    const _86 = "86";

    /**
     * General cargo summary manifest report.
     * A document code to indicate that the message being transmitted is summary manifest information for general cargo.
     */
    const _87 = "87";

    /**
     * Consignment unpack report.
     * A document code to indicate that the message being transmitted is a consignment unpack report only.
     */
    const _88 = "88";

    /**
     * Meat and meat by-products sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that meat or meat by- products comply with the requirements set by the importing country.
     */
    const _89 = "89";

    /**
     * Meat food products sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that meat food products comply with the requirements set by the importing country.
     */
    const _90 = "90";

    /**
     * Poultry sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that poultry products comply with the requirements set by the importing country.
     */
    const _91 = "91";

    /**
     * Horsemeat sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that horsemeat products comply with the requirements set by the importing country.
     */
    const _92 = "92";

    /**
     * Casing sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that casing products comply with the requirements set by the importing country.
     */
    const _93 = "93";

    /**
     * Pharmaceutical sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that pharmaceutical products comply with the requirements set by the importing country.
     */
    const _94 = "94";

    /**
     * Inedible sanitary certificate.
     * Document or message issued by the competent authority in the exporting country evidencing that inedible products comply with the requirements set by the importing country.
     */
    const _95 = "95";

    /**
     * Impending arrival.
     * Notification of impending arrival details for vessel.
     */
    const _96 = "96";

    /**
     * Means of transport advice.
     * Message reporting the means of transport used to carry goods or cargo.
     */
    const _97 = "97";

    /**
     * Arrival information.
     * Message reporting the arrival details of goods or cargo.
     */
    const _98 = "98";

    /**
     * Cargo release notification.
     * Message/document sent by the cargo handler indicating that the cargo has moved from a Customs controlled premise.
     */
    const _99 = "99";

    /**
     * Excise certificate.
     * Certificate asserting that the goods have been submitted to the excise authorities before departure from the exporting country or before delivery in case of import traffic.
     */
    const _100 = "100";

    /**
     * Registration document.
     * An official document providing registration details.
     */
    const _101 = "101";

    /**
     * Tax notification.
     * Used to specify that the message is a tax notification.
     */
    const _102 = "102";

    /**
     * Transport equipment direct interchange report.
     * Report on the movement of containers or other items of transport equipment being exchanged, establishing relevant rental periods.
     */
    const _103 = "103";

    /**
     * Transport equipment impending arrival advice.
     * Advice that containers or other items of transport equipment may be expected to be delivered to a certain location.
     */
    const _104 = "104";

    /**
     * Purchase order.
     * Document/message issued within an enterprise to initiate the purchase of articles, materials or services required for the production or manufacture of goods to be offered for sale or otherwise supplied to customers.
     */
    const _105 = "105";

    /**
     * Transport equipment damage report.
     * Report of damaged items of transport equipment that have been returned.
     */
    const _106 = "106";

    /**
     * Transport equipment maintenance and repair work estimate.
     * advice Advice providing estimates of transport equipment maintenance and repair costs.
     */
    const _107 = "107";

    /**
     * Transport equipment empty release instruction.
     * Instruction to release an item of empty transport equipment to a specified party or parties.
     */
    const _108 = "108";

    /**
     * Transport movement gate in report.
     * Report on the inward movement of cargo, containers or other items of transport equipment which have been delivered to a facility by an inland carrier.
     */
    const _109 = "109";

    /**
     * Manufacturing instructions.
     * Document/message issued within an enterprise to initiate the manufacture of goods to be offered for sale.
     */
    const _110 = "110";

    /**
     * Transport movement gate out report.
     * Report on the outward movement of cargo, containers or other items of transport equipment (either full or empty) which have been picked up by an inland carrier.
     */
    const _111 = "111";

    /**
     * Transport equipment unpacking instruction.
     * Instruction to unpack specified cargo from specified containers or other items of transport equipment.
     */
    const _112 = "112";

    /**
     * Transport equipment unpacking report.
     * Report on the completion of unpacking specified containers or other items of transport equipment.
     */
    const _113 = "113";

    /**
     * Transport equipment pick-up availability request.
     * Request for confirmation that an item of transport equipment will be available for collection.
     */
    const _114 = "114";

    /**
     * Transport equipment pick-up availability confirmation.
     * Confirmation that an item of transport equipment is available for collection.
     */
    const _115 = "115";

    /**
     * Transport equipment pick-up report.
     * Report that an item of transport equipment has been collected.
     */
    const _116 = "116";

    /**
     * Transport equipment shift report.
     * Report on the movement of containers or other items of transport within a facility.
     */
    const _117 = "117";

    /**
     * Transport discharge instruction.
     * Instruction to unload specified cargo, containers or transport equipment from a means of transport.
     */
    const _118 = "118";

    /**
     * Transport discharge report.
     * Report on cargo, containers or transport equipment unloaded from a particular means of transport.
     */
    const _119 = "119";

    /**
     * Stores requisition.
     * Document/message issued within an enterprise ordering the taking out of stock of goods.
     */
    const _120 = "120";

    /**
     * Transport loading instruction.
     * Instruction to load cargo, containers or transport equipment onto a means of transport.
     */
    const _121 = "121";

    /**
     * Transport loading report.
     * Report on completion of loading cargo, containers or other transport equipment onto a means of transport.
     */
    const _122 = "122";

    /**
     * Transport equipment maintenance and repair work.
     * authorisation Authorisation to have transport equipment repaired or to have maintenance performed.
     */
    const _123 = "123";

    /**
     * Transport departure report.
     * Report of the departure of a means of transport from a particular facility.
     */
    const _124 = "124";

    /**
     * Transport empty equipment advice.
     * Advice that an item or items of empty transport equipment are available for return.
     */
    const _125 = "125";

    /**
     * Transport equipment acceptance order.
     * Order to accept items of transport equipment which are to be delivered by an inland carrier (rail, road or barge) to a specified facility.
     */
    const _126 = "126";

    /**
     * Transport equipment special service instruction.
     * Instruction to perform a specified service or services on an item or items of transport equipment.
     */
    const _127 = "127";

    /**
     * Transport equipment stock report.
     * Report on the number of items of transport equipment stored at one or more locations.
     */
    const _128 = "128";

    /**
     * Transport cargo release order.
     * Order to release cargo or items of transport equipment to a specified party.
     */
    const _129 = "129";

    /**
     * Invoicing data sheet.
     * Document/message issued within an enterprise containing data about goods sold, to be used as the basis for the preparation of an invoice.
     */
    const _130 = "130";

    /**
     * Transport equipment packing instruction.
     * Instruction to pack cargo into a container or other item of transport equipment.
     */
    const _131 = "131";

    /**
     * Customs clearance notice.
     * Notification of customs clearance of cargo or items of transport equipment.
     */
    const _132 = "132";

    /**
     * Customs documents expiration notice.
     * Notice specifying expiration of Customs documents relating to cargo or items of transport equipment.
     */
    const _133 = "133";

    /**
     * Transport equipment on-hire request.
     * Request for transport equipment to be made available for hire.
     */
    const _134 = "134";

    /**
     * Transport equipment on-hire order.
     * Order to release empty items of transport equipment for on-hire to a lessee, and authorising collection by or on behalf of a specified party.
     */
    const _135 = "135";

    /**
     * Transport equipment off-hire request.
     * Request to terminate the lease on an item of transport equipment at a specified time.
     */
    const _136 = "136";

    /**
     * Transport equipment survey order.
     * Order to perform a survey on specified items of transport equipment.
     */
    const _137 = "137";

    /**
     * Transport equipment survey order response.
     * Response to an order to conduct a survey of transport equipment.
     */
    const _138 = "138";

    /**
     * Transport equipment survey report.
     * Survey report of specified items of transport equipment.
     */
    const _139 = "139";

    /**
     * Packing instructions.
     * Document/message within an enterprise giving instructions on how goods are to be packed.
     */
    const _140 = "140";

    /**
     * Advising items to be booked to a financial account.
     * A document and/or message advising of items which have to be booked to a financial account.
     */
    const _141 = "141";

    /**
     * Transport equipment maintenance and repair work estimate.
     * order Order to draw up an estimate of the costs of maintenance or repair of transport equipment.
     */
    const _142 = "142";

    /**
     * Transport equipment maintenance and repair notice.
     * Report of transport equipment which has been repaired or has had maintenance performed.
     */
    const _143 = "143";

    /**
     * Empty container disposition order.
     * Order to make available empty containers.
     */
    const _144 = "144";

    /**
     * Cargo vessel discharge order.
     * Order that the containers or cargo specified are to be discharged from a vessel.
     */
    const _145 = "145";

    /**
     * Cargo vessel loading order.
     * Order that specified cargo, containers or groups of containers are to be loaded in or on a vessel.
     */
    const _146 = "146";

    /**
     * Multidrop order.
     * One purchase order that contains the orders of two or more vendors and the associated delivery points for each.
     */
    const _147 = "147";

    /**
     * Bailment contract.
     * A document authorizing the bailing of goods.
     */
    const _148 = "148";

    /**
     * Basic agreement.
     * A document indicating an agreement containing basic terms and conditions applicable to future contracts between two parties.
     */
    const _149 = "149";

    /**
     * Internal transport order.
     * Document/message giving instructions about the transport of goods within an enterprise.
     */
    const _150 = "150";

    /**
     * Grant.
     * A document indicating the granting of funds.
     */
    const _151 = "151";

    /**
     * Indefinite delivery indefinite quantity contract.
     * A document indicating a contract calling for the indefinite deliveries of indefinite quantities of goods.
     */
    const _152 = "152";

    /**
     * Indefinite delivery definite quantity contract.
     * A document indicating a contract calling for indefinite deliveries of definite quantities.
     */
    const _153 = "153";

    /**
     * Requirements contract.
     * A document indicating a requirements contract that authorizes the filling of all purchase requirements during a specified contract period.
     */
    const _154 = "154";

    /**
     * Task order.
     * A document indicating an order that tasks a contractor to perform a specified function.
     */
    const _155 = "155";

    /**
     * Make or buy plan.
     * A document indicating a plan that identifies which items will be made and which items will be bought.
     */
    const _156 = "156";

    /**
     * Subcontractor plan.
     * A document indicating a plan that identifies the manufacturer's subcontracting strategy for a specific contract.
     */
    const _157 = "157";

    /**
     * Cost data summary.
     * A document indicating a summary of cost data.
     */
    const _158 = "158";

    /**
     * Certified cost and price data.
     * A document indicating cost and price data whose accuracy has been certified.
     */
    const _159 = "159";

    /**
     * Wage determination.
     * A document indicating a determination of the wages to be paid.
     */
    const _160 = "160";

    /**
     * Contract Funds Status Report (CFSR).
     * A report to provide the status of funds applicable to the contract.
     */
    const _161 = "161";

    /**
     * Certified inspection and test results.
     * A certification as to the accuracy of inspection and test results.
     */
    const _162 = "162";

    /**
     * Material inspection and receiving report.
     * A report that is both an inspection report for materials and a receiving document.
     */
    const _163 = "163";

    /**
     * Purchasing specification.
     * A document indicating a specification used to purchase an item.
     */
    const _164 = "164";

    /**
     * Payment or performance bond.
     * A document indicating a bond that guarantees the payment of monies or a performance.
     */
    const _165 = "165";

    /**
     * Contract security classification specification.
     * A document that indicates the specification contains the security and classification requirements for a contract.
     */
    const _166 = "166";

    /**
     * Manufacturing specification.
     * A document indicating the specification of how an item is to be manufactured.
     */
    const _167 = "167";

    /**
     * Buy America certificate of compliance.
     * A document certifying that more than 50 percent of the cost of an item is attributed to US origin.
     */
    const _168 = "168";

    /**
     * Container off-hire notice.
     * Notice to return leased containers.
     */
    const _169 = "169";

    /**
     * Cargo acceptance order.
     * Order to accept cargo to be delivered by a carrier.
     */
    const _170 = "170";

    /**
     * Pick-up notice.
     * Notice specifying the pick-up of released cargo or containers from a certain address.
     */
    const _171 = "171";

    /**
     * Authorisation to plan and suggest orders.
     * Document or message that authorises receiver to plan orders, based on information in this message, and send these orders as suggestions to the sender.
     */
    const _172 = "172";

    /**
     * Authorisation to plan and ship orders.
     * Document or message that authorises receiver to plan and ship orders based on information in this message.
     */
    const _173 = "173";

    /**
     * Drawing.
     * The document or message is a drawing.
     */
    const _174 = "174";

    /**
     * Cost Performance Report (CPR) format 2.
     * A report identifying the cost performance on a contract at specified levels of the work breakdown structure (format 2 - organizational categories).
     */
    const _175 = "175";

    /**
     * Cost Schedule Status Report (CSSR).
     * A report providing the status of the cost and schedule applicable to a contract.
     */
    const _176 = "176";

    /**
     * Cost Performance Report (CPR) format 1.
     * A report identifying the cost performance on a contract including the current month's values at specified levels of the work breakdown structure (format 1 - work breakdown structure).
     */
    const _177 = "177";

    /**
     * Cost Performance Report (CPR) format 3.
     * A report identifying the cost performance on a contract that summarizes changes to a contract over a given reporting period with beginning and ending values (format 3 - baseline).
     */
    const _178 = "178";

    /**
     * Cost Performance Report (CPR) format 4.
     * A report identifying the cost performance on a contract including forecasts of labour requirements for the remaining portion of the contract (format 4 - staffing).
     */
    const _179 = "179";

    /**
     * Cost Performance Report (CPR) format 5.
     * A report identifying the cost performance on a contract that summarizes cost or schedule variances (format 5 - explanations and problem analysis).
     */
    const _180 = "180";

    /**
     * Progressive discharge report.
     * Document or message progressively issued by the container terminal operator in charge of discharging a vessel identifying containers that have been discharged from a specific vessel at that point in time.
     */
    const _181 = "181";

    /**
     * Balance confirmation.
     * Confirmation of a balance at an entry date.
     */
    const _182 = "182";

    /**
     * Container stripping order.
     * Order to unload goods from a container.
     */
    const _183 = "183";

    /**
     * Container stuffing order.
     * Order to stuff specified goods or consignments in a container.
     */
    const _184 = "184";

    /**
     * Conveyance declaration (arrival).
     * Declaration to the public authority upon arrival of the conveyance.
     */
    const _185 = "185";

    /**
     * Conveyance declaration (departure).
     * Declaration to the public authority upon departure of the conveyance.
     */
    const _186 = "186";

    /**
     * Conveyance declaration (combined).
     * Combined declaration of arrival and departure to the public authority.
     */
    const _187 = "187";

    /**
     * Project recovery plan.
     * A project plan for recovery after a delay or problem resolution.
     */
    const _188 = "188";

    /**
     * Project production plan.
     * A project plan for the production of goods.
     */
    const _189 = "189";

    /**
     * Statistical and other administrative internal documents.
     * Documents/messages issued within an enterprise for the for the purpose of collection of production and other internal statistics, and for other administration purposes.
     */
    const _190 = "190";

    /**
     * Project master schedule.
     * A high level, all encompassing master schedule of activities to complete a project.
     */
    const _191 = "191";

    /**
     * Priced alternate tender bill of quantity.
     * A priced tender based upon an alternate specification.
     */
    const _192 = "192";

    /**
     * Estimated priced bill of quantity.
     * An estimate based upon a detailed, quantity based specification (bill of quantity).
     */
    const _193 = "193";

    /**
     * Draft bill of quantity.
     * Document/message providing a draft bill of quantity, issued in an unpriced form.
     */
    const _194 = "194";

    /**
     * Documentary credit collection instruction.
     * Instruction for the collection of the documentary credit.
     */
    const _195 = "195";

    /**
     * Request for an amendment of a documentary credit.
     * Request for an amendment of a documentary credit.
     */
    const _196 = "196";

    /**
     * Documentary credit amendment information.
     * Documentary credit amendment information.
     */
    const _197 = "197";

    /**
     * Advice of an amendment of a documentary credit.
     * Advice of an amendment of a documentary credit.
     */
    const _198 = "198";

    /**
     * Response to an amendment of a documentary credit.
     * Response to an amendment of a documentary credit.
     */
    const _199 = "199";

    /**
     * Documentary credit issuance information.
     * Provides information on documentary credit issuance.
     */
    const _200 = "200";

    /**
     * Direct payment valuation request.
     * Request to establish a direct payment valuation.
     */
    const _201 = "201";

    /**
     * Direct payment valuation.
     * Document/message addressed, for instance, by a general contractor to the owner, in order that a direct payment be made to a subcontractor.
     */
    const _202 = "202";

    /**
     * Provisional payment valuation.
     * Document/message establishing a provisional payment valuation.
     */
    const _203 = "203";

    /**
     * Payment valuation.
     * Document/message establishing the financial elements of a situation of works.
     */
    const _204 = "204";

    /**
     * Quantity valuation.
     * Document/message providing a confirmed assessment, by quantity, of the completed work for a construction contract.
     */
    const _205 = "205";

    /**
     * Quantity valuation request.
     * Document/message providing an initial assessment, by quantity, of the completed work for a construction contract.
     */
    const _206 = "206";

    /**
     * Contract bill of quantities - BOQ.
     * Document/message providing a formal specification identifying quantities and prices that are the basis of a contract for a construction project. BOQ means: Bill of quantity.
     */
    const _207 = "207";

    /**
     * Unpriced bill of quantity.
     * Document/message providing a detailed, quantity based specification, issued in an unpriced form to invite tender prices.
     */
    const _208 = "208";

    /**
     * Priced tender BOQ.
     * Document/message providing a detailed, quantity based specification, updated with prices to form a tender submission for a construction contract. BOQ means: Bill of quantity.
     */
    const _209 = "209";

    /**
     * Enquiry.
     * Document/message issued by a party interested in the purchase of goods specified therein and indicating particular, desirable conditions regarding delivery terms, etc., addressed to a prospective supplier with a view to obtaining an offer.
     */
    const _210 = "210";

    /**
     * Interim application for payment.
     * Document/message containing a provisional assessment in support of a request for payment for completed work for a construction contract.
     */
    const _211 = "211";

    /**
     * Agreement to pay.
     * Document/message in which the debtor expresses the intention to pay.
     */
    const _212 = "212";

    /**
     * Request for financial cancellation.
     * The message is a request for financial cancellation.
     */
    const _213 = "213";

    /**
     * Pre-authorised direct debit(s).
     * The message contains pre-authorised direct debit(s).
     */
    const _214 = "214";

    /**
     * Letter of intent.
     * Document/message by means of which a buyer informs a seller that the buyer intends to enter into contractual negotiations.
     */
    const _215 = "215";

    /**
     * Approved unpriced bill of quantity.
     * Document/message providing an approved detailed, quantity based specification (bill of quantity), in an unpriced form.
     */
    const _216 = "216";

    /**
     * Payment valuation for unscheduled items.
     * A payment valuation for unscheduled items.
     */
    const _217 = "217";

    /**
     * Final payment request based on completion of work.
     * The final payment request of a series of payment requests submitted upon completion of all the work.
     */
    const _218 = "218";

    /**
     * Payment request for completed units.
     * A request for payment for completed units.
     */
    const _219 = "219";

    /**
     * Order.
     * Document/message by means of which a buyer initiates a transaction with a seller involving the supply of goods or services as specified, according to conditions set out in an offer, or otherwise known to the buyer.
     */
    const _220 = "220";

    /**
     * Blanket order.
     * Usage of document/message for general order purposes with later split into quantities and delivery dates and maybe delivery locations.
     */
    const _221 = "221";

    /**
     * Spot order.
     * Document/message ordering the remainder of a production's batch.
     */
    const _222 = "222";

    /**
     * Lease order.
     * Document/message for goods in leasing contracts.
     */
    const _223 = "223";

    /**
     * Rush order.
     * Document/message for urgent ordering.
     */
    const _224 = "224";

    /**
     * Repair order.
     * Document/message to order repair of goods.
     */
    const _225 = "225";

    /**
     * Call off order.
     * Document/message to provide split quantities and delivery dates referring to a previous blanket order.
     */
    const _226 = "226";

    /**
     * Consignment order.
     * Order to deliver goods into stock with agreement on payment when goods are sold out of this stock.
     */
    const _227 = "227";

    /**
     * Sample order.
     * Document/message to order samples.
     */
    const _228 = "228";

    /**
     * Swap order.
     * Document/message informing buyer or seller of the replacement of goods previously ordered.
     */
    const _229 = "229";

    /**
     * Purchase order change request.
     * Change to an purchase order already sent.
     */
    const _230 = "230";

    /**
     * Purchase order response.
     * Response to an purchase order already received.
     */
    const _231 = "231";

    /**
     * Hire order.
     * Document/message for hiring human resources or renting goods or equipment.
     */
    const _232 = "232";

    /**
     * Spare parts order.
     * Document/message to order spare parts.
     */
    const _233 = "233";

    /**
     * Campaign price/sales catalogue.
     * A price/sales catalogue containing special prices which are valid only for a specified period or under specified conditions.
     */
    const _234 = "234";

    /**
     * Container list.
     * Document or message issued by party identifying the containers for which they are responsible.
     */
    const _235 = "235";

    /**
     * Delivery forecast.
     * A message which enables the transmission of delivery or product forecasting requirements.
     */
    const _236 = "236";

    /**
     * Cross docking services order.
     * A document or message to order cross docking services.
     */
    const _237 = "237";

    /**
     * Non-pre-authorised direct debit(s).
     * The message contains non-pre-authorised direct debit(s).
     */
    const _238 = "238";

    /**
     * Rejected direct debit(s).
     * The message contains rejected direct debit(s).
     */
    const _239 = "239";

    /**
     * Delivery instructions.
     * (1174) Document/message giving instruction regarding the delivery of goods.
     */
    const _240 = "240";

    /**
     * Delivery schedule.
     * Usage of DELFOR-message.
     */
    const _241 = "241";

    /**
     * Delivery just-in-time.
     * Usage of DELJIT-message.
     */
    const _242 = "242";

    /**
     * Pre-authorised direct debit request(s).
     * The message contains pre-authorised direct debit request(s).
     */
    const _243 = "243";

    /**
     * Non-pre-authorised direct debit request(s).
     * The message contains non-pre-authorised direct debit request(s).
     */
    const _244 = "244";

    /**
     * Delivery release.
     * Document/message issued by a buyer releasing the despatch of goods after receipt of the Ready for despatch advice from the seller.
     */
    const _245 = "245";

    /**
     * Settlement of a letter of credit.
     * Settlement of a letter of credit.
     */
    const _246 = "246";

    /**
     * Bank to bank funds transfer.
     * The message is a bank to bank funds transfer.
     */
    const _247 = "247";

    /**
     * Customer payment order(s).
     * The message contains customer payment order(s).
     */
    const _248 = "248";

    /**
     * Low value payment order(s).
     * The message contains low value payment order(s) only.
     */
    const _249 = "249";

    /**
     * Crew list declaration.
     * Declaration regarding crew members aboard the conveyance.
     */
    const _250 = "250";

    /**
     * Inquiry.
     * This is a request for information.
     */
    const _251 = "251";

    /**
     * Response to previous banking status message.
     * A response to a previously sent banking status message.
     */
    const _252 = "252";

    /**
     * Project master plan.
     * A high level, all encompassing master plan to complete a project.
     */
    const _253 = "253";

    /**
     * Project plan.
     * A plan for project work to be completed.
     */
    const _254 = "254";

    /**
     * Project schedule.
     * A schedule of project activities to be completed.
     */
    const _255 = "255";

    /**
     * Project planning available resources.
     * Available resources for project planning purposes.
     */
    const _256 = "256";

    /**
     * Project planning calendar.
     * Work calendar information for project planning purposes.
     */
    const _257 = "257";

    /**
     * Standing order.
     * An order to supply fixed quantities of products at fixed regular intervals.
     */
    const _258 = "258";

    /**
     * Cargo movement event log.
     * A document detailing times and dates of events pertaining to a cargo movement.
     */
    const _259 = "259";

    /**
     * Cargo analysis voyage report.
     * An analysis of the cargo for a voyage.
     */
    const _260 = "260";

    /**
     * Self billed credit note.
     * A document which indicates that the customer is claiming credit in a self billing environment.
     */
    const _261 = "261";

    /**
     * Consolidated credit note - goods and services.
     * Credit note for goods and services that covers multiple transactions involving more than one invoice.
     */
    const _262 = "262";

    /**
     * Inventory adjustment status report.
     * A message detailing statuses related to the adjustment of inventory.
     */
    const _263 = "263";

    /**
     * Transport equipment movement instruction.
     * Instruction to perform one or more different movements of transport equipment.
     */
    const _264 = "264";

    /**
     * Transport equipment movement report.
     * Report on one or more different movements of transport equipment.
     */
    const _265 = "265";

    /**
     * Transport equipment status change report.
     * Report on one or more changes of status associated with an item or items of transport equipment.
     */
    const _266 = "266";

    /**
     * Fumigation certificate.
     * Certificate attesting that fumigation has been performed.
     */
    const _267 = "267";

    /**
     * Wine certificate.
     * Certificate attesting to the quality, origin or appellation of wine.
     */
    const _268 = "268";

    /**
     * Wool health certificate.
     * Certificate attesting that wool is free from specified risks to human or animal health.
     */
    const _269 = "269";

    /**
     * Delivery note.
     * Paper document attached to a consignment informing the receiving party about contents of this consignment.
     */
    const _270 = "270";

    /**
     * Packing list.
     * Document/message specifying the distribution of goods in individual packages (in trade environment the despatch advice message is used for the packing list).
     */
    const _271 = "271";

    /**
     * New code request.
     * Requesting a new code.
     */
    const _272 = "272";

    /**
     * Code change request.
     * Request a change to an existing code.
     */
    const _273 = "273";

    /**
     * Simple data element request.
     * Requesting a new simple data element.
     */
    const _274 = "274";

    /**
     * Simple data element change request.
     * Request a change to an existing simple data element.
     */
    const _275 = "275";

    /**
     * Composite data element request.
     * Requesting a new composite data element.
     */
    const _276 = "276";

    /**
     * Composite data element change request.
     * Request a change to an existing composite data element.
     */
    const _277 = "277";

    /**
     * Segment request.
     * Request a new segment.
     */
    const _278 = "278";

    /**
     * Segment change request.
     * Requesting a change to an existing segment.
     */
    const _279 = "279";

    /**
     * New message request.
     * Request for a new message (NMR).
     */
    const _280 = "280";

    /**
     * Message in development request.
     * Requesting a Message in Development (MiD).
     */
    const _281 = "281";

    /**
     * Modification of existing message.
     * Requesting a change to an existing message.
     */
    const _282 = "282";

    /**
     * Tracking number assignment report.
     * Report of assigned tracking numbers.
     */
    const _283 = "283";

    /**
     * User directory definition.
     * Document/message defining the contents of a user directory set or parts thereof.
     */
    const _284 = "284";

    /**
     * United Nations standard message request.
     * Requesting a United Nations Standard Message (UNSM).
     */
    const _285 = "285";

    /**
     * Service directory definition.
     * Document/message defining the contents of a service directory set or parts thereof.
     */
    const _286 = "286";

    /**
     * Status report.
     * Message covers information about the status.
     */
    const _287 = "287";

    /**
     * Kanban schedule.
     * Message to describe a Kanban schedule.
     */
    const _288 = "288";

    /**
     * Product data message.
     * A message to submit master data, a set of data that is rarely changed, to identify and describe products a supplier offers to their (potential) customer or buyer.
     */
    const _289 = "289";

    /**
     * A claim for parts and/or labour charges.
     * A claim for parts and/or labour charges incurred .
     */
    const _290 = "290";

    /**
     * Delivery schedule response.
     * A message providing a response to a previously transmitted delivery schedule.
     */
    const _291 = "291";

    /**
     * Inspection request.
     * A message requesting a party to inspect items.
     */
    const _292 = "292";

    /**
     * Inspection report.
     * A message informing a party of the results of an inspection.
     */
    const _293 = "293";

    /**
     * Application acknowledgement and error report.
     * A message used by an application to acknowledge reception of a message and/or to report any errors.
     */
    const _294 = "294";

    /**
     * Price variation invoice.
     * An invoice which requests payment for the difference in price between an original invoice and the result of the application of a price variation formula.
     */
    const _295 = "295";

    /**
     * Credit note for price variation.
     * A credit note which is issued against a price variation invoice.
     */
    const _296 = "296";

    /**
     * Instruction to collect.
     * A message instructing a party to collect goods.
     */
    const _297 = "297";

    /**
     * Dangerous goods list.
     * Listing of all details of dangerous goods carried.
     */
    const _298 = "298";

    /**
     * Registration renewal.
     * Code specifying the continued validity of previously submitted registration information.
     */
    const _299 = "299";

    /**
     * Registration change.
     * Code specifying the modification of previously submitted registration information.
     */
    const _300 = "300";

    /**
     * Response to registration.
     * Code specifying a response to an occurrence of a registration message.
     */
    const _301 = "301";

    /**
     * Implementation guideline.
     * A document specifying the criterion and format for exchanging information in an electronic data interchange syntax.
     */
    const _302 = "302";

    /**
     * Request for transfer.
     * Document/message is a request for transfer.
     */
    const _303 = "303";

    /**
     * Cost performance report.
     * A report to convey cost performance data for a project or contract.
     */
    const _304 = "304";

    /**
     * Application error and acknowledgement.
     * A message to inform a message issuer that a previously sent message has been received by the addressee's application, or that a previously sent message has been rejected by the addressee's application.
     */
    const _305 = "305";

    /**
     * Cash pool financial statement.
     * A financial statement for a cash pool.
     */
    const _306 = "306";

    /**
     * Sequenced delivery schedule.
     * Message to describe a sequence of product delivery.
     */
    const _307 = "307";

    /**
     * Delcredere credit note.
     * A credit note sent to the party paying on behalf of a number of buyers.
     */
    const _308 = "308";

    /**
     * Healthcare discharge report, final.
     * Final discharge report by healthcare provider.
     */
    const _309 = "309";

    /**
     * Offer / quotation.
     * (1332) Document/message which, with a view to concluding a contract, sets out the conditions under which the goods are offered.
     */
    const _310 = "310";

    /**
     * Request for quote.
     * Document/message requesting a quote on specified goods or services.
     */
    const _311 = "311";

    /**
     * Acknowledgement message.
     * Message providing acknowledgement information at the business application level concerning the processing of a message.
     */
    const _312 = "312";

    /**
     * Application error message.
     * Message indicating that a message was rejected due to errors encountered at the application level.
     */
    const _313 = "313";

    /**
     * Cargo movement voyage summary.
     * A consolidated voyage summary which contains the information in a certificate of analysis, a voyage analysis and a cargo movement time log for a voyage.
     */
    const _314 = "314";

    /**
     * Contract.
     * (1296) Document/message evidencing an agreement between the seller and the buyer for the supply of goods or services; its effects are equivalent to those of an order followed by an acknowledgement of order.
     */
    const _315 = "315";

    /**
     * Application for usage of berth or mooring facilities.
     * Document to apply for usage of berth or mooring facilities.
     */
    const _316 = "316";

    /**
     * Application for designation of berthing places.
     * Document to apply for designation of berthing places.
     */
    const _317 = "317";

    /**
     * Application for shifting from the designated place in port.
     * Document to apply for shifting from the designated place in port.
     */
    const _318 = "318";

    /**
     * Supplementary document for application for cargo operation.
     * of dangerous goods Supplementary document to apply for cargo operation of dangerous goods.
     */
    const _319 = "319";

    /**
     * Acknowledgement of order.
     * Document/message acknowledging an undertaking to fulfil an order and confirming conditions or acceptance of conditions.
     */
    const _320 = "320";

    /**
     * Supplementary document for application for transport of.
     * dangerous goods Supplementary document to apply for transport of dangerous goods.
     */
    const _321 = "321";

    /**
     * Optical Character Reading (OCR) payment.
     * Payment effected by an Optical Character Reading (OCR) document.
     */
    const _322 = "322";

    /**
     * Preliminary sales report.
     * Preliminary sales report sent before all the information is available.
     */
    const _323 = "323";

    /**
     * Transport emergency card.
     * Official document specifying, for a given dangerous goods item, information such as nature of hazard, protective devices, actions to be taken in case of accident, spillage or fire and first aid to be given.
     */
    const _324 = "324";

    /**
     * Proforma invoice.
     * Document/message serving as a preliminary invoice, containing - on the whole - the same information as the final invoice, but not actually claiming payment.
     */
    const _325 = "325";

    /**
     * Partial invoice.
     * Document/message specifying details of an incomplete invoice.
     */
    const _326 = "326";

    /**
     * Operating instructions.
     * Document/message describing instructions for operation.
     */
    const _327 = "327";

    /**
     * Name/product plate.
     * Plates on goods identifying and describing an article.
     */
    const _328 = "328";

    /**
     * Co-insurance ceding bordereau.
     * The document or message contains a bordereau describing co-insurance ceding information.
     */
    const _329 = "329";

    /**
     * Request for delivery instructions.
     * Document/message issued by a supplier requesting instructions from the buyer regarding the details of the delivery of goods ordered.
     */
    const _330 = "330";

    /**
     * Commercial invoice which includes a packing list.
     * Commercial transaction (invoice) will include a packing list.
     */
    const _331 = "331";

    /**
     * Trade data.
     * Document/message is for trade data.
     */
    const _332 = "332";

    /**
     * Customs declaration for cargo examination.
     * Declaration provided to customs for cargo examination.
     */
    const _333 = "333";

    /**
     * Customs declaration for cargo examination, alternate.
     * Alternate declaration provided to customs for cargo examination.
     */
    const _334 = "334";

    /**
     * Booking request.
     * Document/message issued by a supplier to a carrier requesting space to be reserved for a specified consignment, indicating desirable conveyance, despatch time, etc.
     */
    const _335 = "335";

    /**
     * Customs crew and conveyance.
     * Document/message contains information regarding the crew list and conveyance.
     */
    const _336 = "336";

    /**
     * Customs summary declaration with commercial detail, alternate.
     * Alternate Customs declaration summary with commercial transaction details.
     */
    const _337 = "337";

    /**
     * Items booked to a financial account report.
     * A message reporting items which have been booked to a financial account.
     */
    const _338 = "338";

    /**
     * Report of transactions which need further information from.
     * the receiver A message reporting transactions which need further information from the receiver.
     */
    const _339 = "339";

    /**
     * Shipping instructions.
     * (1121) Document/message advising details of cargo and exporter's requirements for its physical movement.
     */
    const _340 = "340";

    /**
     * Shipper's letter of instructions (air).
     * Document/message issued by a consignor in which he gives details of a consignment of goods that enables an airline or its agent to prepare an air waybill.
     */
    const _341 = "341";

    /**
     * Report of transactions for information only.
     * A message reporting transactions for information only.
     */
    const _342 = "342";

    /**
     * Cartage order (local transport).
     * Document/message giving instructions regarding local transport of goods, e.g. from the premises of an enterprise to those of a carrier undertaking further transport.
     */
    const _343 = "343";

    /**
     * EDI associated object administration message.
     * A message giving additional information about the exchange of an EDI associated object.
     */
    const _344 = "344";

    /**
     * Ready for despatch advice.
     * Document/message issued by a supplier informing a buyer that goods ordered are ready for despatch.
     */
    const _345 = "345";

    /**
     * Summary sales report.
     * Sales report containing summaries for several earlier sent sales reports.
     */
    const _346 = "346";

    /**
     * Order status enquiry.
     * A message enquiring the status of previously sent orders.
     */
    const _347 = "347";

    /**
     * Order status report.
     * A message reporting the status of previously sent orders.
     */
    const _348 = "348";

    /**
     * Declaration regarding the inward and outward movement of.
     * vessel Document to declare inward and outward movement of a vessel.
     */
    const _349 = "349";

    /**
     * Despatch order.
     * Document/message issued by a supplier initiating the despatch of goods to a buyer (consignee).
     */
    const _350 = "350";

    /**
     * Despatch advice.
     * Document/message by means of which the seller or consignor informs the consignee about the despatch of goods.
     */
    const _351 = "351";

    /**
     * Notification of usage of berth or mooring facilities.
     * Document to notify usage of berth or mooring facilities.
     */
    const _352 = "352";

    /**
     * Application for vessel's entering into port area in night-
     * time Document to apply for vessel's entering into port area in night-time.
     */
    const _353 = "353";

    /**
     * Notification of emergency shifting from the designated
     * place in port Document to notify shifting from designated place in port once secured at the designated place.
     */
    const _354 = "354";

    /**
     * Customs summary declaration without commercial detail, alternate.
     * Alternate Customs declaration summary without any commercial transaction details.
     */
    const _355 = "355";

    /**
     * Performance bond.
     * A document that guarantees performance.
     */
    const _356 = "356";

    /**
     * Payment bond.
     * A document that guarantees the payment of monies.
     */
    const _357 = "357";

    /**
     * Healthcare discharge report, preliminary.
     * Preliminary discharge report by healthcare provider.
     */
    const _358 = "358";

    /**
     * Request for provision of a health service.
     * Document containing request for provision of a health service.
     */
    const _359 = "359";

    /**
     * Request for price quote.
     * Document/message requesting price conditions under which goods are offered.
     */
    const _360 = "360";

    /**
     * Price quote.
     * Document/message confirming price conditions under which goods are offered.
     */
    const _361 = "361";

    /**
     * Delivery quote.
     * Document/message confirming delivery conditions under which goods are offered.
     */
    const _362 = "362";

    /**
     * Price and delivery quote.
     * Document/message confirming price and delivery conditions under which goods are offered.
     */
    const _363 = "363";

    /**
     * Contract price quote.
     * Document/message confirming contractual price conditions under which goods are offered.
     */
    const _364 = "364";

    /**
     * Contract price and delivery quote.
     * Document/message confirming contractual price conditions and contractual delivery conditions under which goods are offered.
     */
    const _365 = "365";

    /**
     * Price quote, specified end-customer.
     * Document/message confirming price conditions under which goods are offered, provided that they are sold to the end-customer specified on the quote.
     */
    const _366 = "366";

    /**
     * Price and delivery quote, specified end-customer.
     * Document/message confirming price conditions and delivery conditions under which goods are offered, provided that they are sold to the end-customer specified on the quote.
     */
    const _367 = "367";

    /**
     * Price quote, ship and debit.
     * Document/message from a supplier to a distributor confirming price conditions under which goods can be sold by a distributor to the end-customer specified on the quote with compensation for loss of inventory value.
     */
    const _368 = "368";

    /**
     * Price and delivery quote, ship and debit.
     * Document/message from a supplier to a distributor confirming price conditions and delivery conditions under which goods can be sold by a distributor to the end-customer specified on the quote with compensation for loss of inventory value.
     */
    const _369 = "369";

    /**
     * Advice of distribution of documents.
     * Document/message in which the party responsible for the issue of a set of trade documents specifies the various recipients of originals and copies of these documents, with an indication of the number of copies distributed to each of them.
     */
    const _370 = "370";

    /**
     * Plan for provision of health service.
     * Document containing a plan for provision of health service.
     */
    const _371 = "371";

    /**
     * Prescription.
     * Instructions for the dispensing and use of medicine or remedy.
     */
    const _372 = "372";

    /**
     * Prescription request.
     * Request to issue a prescription for medicine or remedy.
     */
    const _373 = "373";

    /**
     * Prescription dispensing report.
     * Document containing information of products dispensed according to a prescription.
     */
    const _374 = "374";

    /**
     * Certificate of shipment.
     * (1109) Certificate providing confirmation that a consignment has been shipped.
     */
    const _375 = "375";

    /**
     * Standing inquiry on product information.
     * A product inquiry which stands until it is cancelled.
     */
    const _376 = "376";

    /**
     * Party credit information.
     * Document/message providing data concerning the credit information of a party.
     */
    const _377 = "377";

    /**
     * Party payment behaviour information.
     * Document/message providing data concerning the payment behaviour of a party.
     */
    const _378 = "378";

    /**
     * Request for metering point information.
     * Message to request information about a metering point.
     */
    const _379 = "379";

    /**
     * Commercial invoice.
     * (1334) Document/message claiming payment for goods or services supplied under conditions agreed between seller and buyer.
     */
    const _380 = "380";

    /**
     * Credit note.
     * (1113) Document/message for providing credit information to the relevant party.
     */
    const _381 = "381";

    /**
     * Commission note.
     * (1111) Document/message in which a seller specifies the amount of commission, the percentage of the invoice amount, or some other basis for the calculation of the commission to which a sales agent is entitled.
     */
    const _382 = "382";

    /**
     * Debit note.
     * Document/message for providing debit information to the relevant party.
     */
    const _383 = "383";

    /**
     * Corrected invoice.
     * Commercial invoice that includes revised information differing from an earlier submission of the same invoice.
     */
    const _384 = "384";

    /**
     * Consolidated invoice.
     * Commercial invoice that covers multiple transactions involving more than one vendor.
     */
    const _385 = "385";

    /**
     * Prepayment invoice.
     * An invoice to pay amounts for goods and services in advance; these amounts will be subtracted from the final invoice.
     */
    const _386 = "386";

    /**
     * Hire invoice.
     * Document/message for invoicing the hiring of human resources or renting goods or equipment.
     */
    const _387 = "387";

    /**
     * Tax invoice.
     * An invoice for tax purposes.
     */
    const _388 = "388";

    /**
     * Self-billed invoice.
     * An invoice the invoicee is producing instead of the seller.
     */
    const _389 = "389";

    /**
     * Delcredere invoice.
     * An invoice sent to the party paying for a number of buyers.
     */
    const _390 = "390";

    /**
     * Metering point information response.
     * Response to a request for information about a metering point.
     */
    const _391 = "391";

    /**
     * Notification of change of supplier.
     * A notification of a change of supplier.
     */
    const _392 = "392";

    /**
     * Factored invoice.
     * Invoice assigned to a third party for collection.
     */
    const _393 = "393";

    /**
     * Lease invoice.
     * Usage of INVOIC-message for goods in leasing contracts.
     */
    const _394 = "394";

    /**
     * Consignment invoice.
     * Commercial invoice that covers a transaction other than one involving a sale.
     */
    const _395 = "395";

    /**
     * Factored credit note.
     * Credit note related to assigned invoice(s).
     */
    const _396 = "396";

    /**
     * Commercial account summary response.
     * A document providing a response to a previously sent commercial account summary message.
     */
    const _397 = "397";

    /**
     * Cross docking despatch advice.
     * Document by means of which the supplier or consignor informs the buyer, consignee or the distribution centre about the despatch of goods for cross docking.
     */
    const _398 = "398";

    /**
     * Transshipment despatch advice.
     * Document by means of which the supplier or consignor informs the buyer, consignee or the distribution centre about the despatch of goods for transshipment.
     */
    const _399 = "399";

    /**
     * Exceptional order.
     * An order which falls outside the framework of an agreement.
     */
    const _400 = "400";

    /**
     * Pre-packed cross docking order.
     * An order requesting the supply of products packed according to the final delivery point which will be moved across a dock in a distribution centre without further handling.
     */
    const _401 = "401";

    /**
     * Intermediate handling cross docking order.
     * An order requesting the supply of products which will be moved across a dock, de-consolidated and re-consolidated according to the final delivery location requirements.
     */
    const _402 = "402";

    /**
     * Means of transportation availability information.
     * Information giving the various availabilities of a means of transportation.
     */
    const _403 = "403";

    /**
     * Means of transportation schedule information.
     * Information giving the various schedules of a means of transportation.
     */
    const _404 = "404";

    /**
     * Transport equipment delivery notice.
     * Notification regarding the delivery of transport equipment.
     */
    const _405 = "405";

    /**
     * Notification to supplier of contract termination.
     * Notification to the supplier regarding the termination of a contract.
     */
    const _406 = "406";

    /**
     * Notification to supplier of metering point changes.
     * Notification to the supplier about changes regarding a metering point.
     */
    const _407 = "407";

    /**
     * Notification of meter change.
     * Notification about the change of a meter.
     */
    const _408 = "408";

    /**
     * Instructions for bank transfer.
     * Document/message containing instructions from a customer to his bank to pay an amount in a specified currency to a nominated party in another country by a method either specified (e.g. teletransmission, air mail) or left to the discretion of the bank.
     */
    const _409 = "409";

    /**
     * Notification of metering point identification change.
     * Notification of the change of metering point identification.
     */
    const _410 = "410";

    /**
     * Utilities time series message.
     * The Utilities time series message is sent between responsible parties in a utilities infrastructure for the purpose of reporting time series and connected technical and/or administrative information.
     */
    const _411 = "411";

    /**
     * Application for banker's draft.
     * Application by a customer to his bank to issue a banker's draft stating the amount and currency of the draft, the name of the payee and the place and country of payment.
     */
    const _412 = "412";

    /**
     * Infrastructure condition.
     * Information about components in an infrastructure.
     */
    const _413 = "413";

    /**
     * Acknowledgement of change of supplier.
     * Acknowledgement of the change of supplier.
     */
    const _414 = "414";

    /**
     * Data Plot Sheet.
     * Document/Message providing technical description and information of the crop production.
     */
    const _415 = "415";

    /**
     * Soil analysis.
     * Soil analysis document.
     */
    const _416 = "416";

    /**
     * Farmyard manure analysis.
     * Farmyard manure analysis document.
     */
    const _417 = "417";

    /**
     * WCO Cargo Report Export, Rail or Road.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs concerning the export of cargo carried by commercial means of transport over land, e.g. truck or train.
     */
    const _418 = "418";

    /**
     * WCO Cargo Report Export, Air or Maritime.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs concerning the export of cargo carried by commercial means of transport over water or through the air, e.g. vessel or aircraft.
     */
    const _419 = "419";

    /**
     * Optical Character Reading (OCR) payment credit note.
     * Payment credit note effected by an Optical Character Reading (OCR) document.
     */
    const _420 = "420";

    /**
     * WCO Cargo Report Import, Rail or Road.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs concerning the import of cargo carried by commercial means of transport over land, e.g. truck or train.
     */
    const _421 = "421";

    /**
     * WCO Cargo Report Import, Air or Maritime.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs concerning the import of cargo carried by commercial means of transport over water or through the air, e.g. vessel or aircraft.
     */
    const _422 = "422";

    /**
     * WCO one-step export declaration.
     * Single step declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for a Customs export procedure based on the
     */
    const _423 = "423";

    /**
     * Kyoto Convention.
     */
    const _1999 = "1999";

    /**
     * WCO first step of two-step export declaration.
     * First part of a simplified declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for Customs export procedure based on the 1999 Kyoto Convention.
     */
    const _424 = "424";

    /**
     * Collection payment advice.
     * Document/message whereby a bank advises that a collection has been paid, giving details and methods of funds disposal.
     */
    const _425 = "425";

    /**
     * Documentary credit payment advice.
     * Document/message whereby a bank advises payment under a documentary credit.
     */
    const _426 = "426";

    /**
     * Documentary credit acceptance advice.
     * Document/message whereby a bank advises acceptance under a documentary credit.
     */
    const _427 = "427";

    /**
     * Documentary credit negotiation advice.
     * Document/message whereby a bank advises negotiation under a documentary credit.
     */
    const _428 = "428";

    /**
     * Application for banker's guarantee.
     * Document/message whereby a customer requests his bank to issue a guarantee in favour of a nominated party in another country, stating the amount and currency and the specific conditions of the guarantee.
     */
    const _429 = "429";

    /**
     * Banker's guarantee.
     * Document/message in which a bank undertakes to pay out a limited amount of money to a designated party, on conditions stated therein (other than those laid down in the Uniform Customs Practice).
     */
    const _430 = "430";

    /**
     * Documentary credit letter of indemnity.
     * Document/message in which a beneficiary of a documentary credit accepts responsibility for non-compliance with the terms and conditions of the credit, and undertakes to refund the money received under the credit, with interest and charges accrued.
     */
    const _431 = "431";

    /**
     * Notification to grid operator of contract termination.
     * Notification to the grid operator regarding the termination of a contract.
     */
    const _432 = "432";

    /**
     * Notification to grid operator of metering point changes.
     * Notification to the grid operator about changes regarding a metering point.
     */
    const _433 = "433";

    /**
     * Notification of balance responsible entity change.
     * Notification of a change of balance responsible entity.
     */
    const _434 = "434";

    /**
     * Preadvice of a credit.
     * Preadvice indicating a credit to happen in the future.
     */
    const _435 = "435";

    /**
     * Transport equipment profile report.
     * Report on the profile of transport equipment.
     */
    const _436 = "436";

    /**
     * Request for price and delivery quote, specified end-user.
     * Document/message requesting price conditions and delivery conditions under which goods are offered, provided that they are sold to the end-customer specified on the request for quote.
     */
    const _437 = "437";

    /**
     * Request for price quote, ship and debit.
     * Document/message from a distributor to a supplier requesting price conditions under which goods can be sold by the distributor to the end-customer specified on the request for quote with compensation for loss of inventory value.
     */
    const _438 = "438";

    /**
     * Request for price and delivery quote, ship and debit.
     * Document/message from a distributor to a supplier requesting price conditions and delivery conditions under which goods can be sold by the distributor to the end-customer specified on the request for quote with compensation for loss of inventory value.
     */
    const _439 = "439";

    /**
     * Delivery point list.
     * A list of delivery point addresses.
     */
    const _440 = "440";

    /**
     * Transport routing information.
     * Document specifying the routes for transport between locations.
     */
    const _441 = "441";

    /**
     * Request for delivery quote.
     * Document/message requesting delivery conditions under which goods are offered.
     */
    const _442 = "442";

    /**
     * Request for price and delivery quote.
     * Document/message requesting price and delivery conditions under which goods are offered.
     */
    const _443 = "443";

    /**
     * Request for contract price quote.
     * Document/message requesting contractual price conditions under which goods are offered.
     */
    const _444 = "444";

    /**
     * Request for contract price and delivery quote.
     * Document/message requesting contractual price conditions and contractual delivery conditions under which goods are offered.
     */
    const _445 = "445";

    /**
     * Request for price quote, specified end-customer.
     * Document/message requesting price conditions under which goods are offered, provided that they are sold to the end-customer specified on the request for quote.
     */
    const _446 = "446";

    /**
     * Collection order.
     * Document/message whereby a bank is instructed (or requested) to handle financial and/or commercial documents in order to obtain acceptance and/or payment, or to deliver documents on such other terms and conditions as may be specified.
     */
    const _447 = "447";

    /**
     * Documents presentation form.
     * Document/message whereby a draft or similar instrument and/or commercial documents are presented to a bank for acceptance, discounting, negotiation, payment or collection, whether or not against a documentary credit.
     */
    const _448 = "448";

    /**
     * Identification match.
     * Message related to conducting a search for an identification match.
     */
    const _449 = "449";

    /**
     * Payment order.
     * Document/message containing information needed to initiate the payment. It may cover the financial settlement for one or more commercial trade transactions. A payment order is an instruction to the ordered bank to arrange for the payment of one specified amount to the beneficiary.
     */
    const _450 = "450";

    /**
     * Extended payment order.
     * Document/message containing information needed to initiate the payment. It may cover the financial settlement for several commercial trade transactions, which it is possible to specify in a special payments detail part. It is an instruction to the ordered bank to arrange for the payment of one specified amount to the beneficiary.
     */
    const _451 = "451";

    /**
     * Multiple payment order.
     * Document/message containing a payment order to debit one or more accounts and to credit one or more beneficiaries.
     */
    const _452 = "452";

    /**
     * Notice that circumstances prevent payment of delivered.
     * goods Message used to inform a supplier that delivered goods cannot be paid due to circumstances which prevent payment.
     */
    const _453 = "453";

    /**
     * Credit advice.
     * Document/message sent by an account servicing institution to one of its account owners, to inform the account owner of an entry which has been or will be credited to its account for a specified amount on the date indicated.
     */
    const _454 = "454";

    /**
     * Extended credit advice.
     * Document/message sent by an account servicing institution to one of its account owners, to inform the account owner of an entry that has been or will be credited to its account for a specified amount on the date indicated. It provides extended commercial information concerning the relevant remittance advice.
     */
    const _455 = "455";

    /**
     * Debit advice
     * Advice on a debit.
     */
    const _456 = "456";

    /**
     * Reversal of debit.
     * Reversal of debit accounting entry by bank.
     */
    const _457 = "457";

    /**
     * Reversal of credit.
     * Reversal of credit accounting entry by bank.
     */
    const _458 = "458";

    /**
     * Travel ticket.
     * The document is a ticket giving access to a travel service.
     */
    const _459 = "459";

    /**
     * Documentary credit application.
     * Document/message whereby a bank is requested to issue a documentary credit on the conditions specified therein.
     */
    const _460 = "460";

    /**
     * Payment card.
     * The document is a credit, guarantee or charge card.
     */
    const _461 = "461";

    /**
     * Ready for transshipment despatch advice.
     * Document to advise that the goods ordered are ready for transshipment.
     */
    const _462 = "462";

    /**
     * Pre-packed cross docking despatch advice.
     * Document by means of which the supplier or consignor informs the buyer, consignee or distribution centre about the despatch of products packed according to the final delivery point requirements which will be moved across a dock in a distribution centre without further handling.
     */
    const _463 = "463";

    /**
     * Intermediate handling cross docking despatch advice.
     * Document by means of which the supplier or consignor informs the buyer, consignee or the distribution centre about the despatch of products which will be moved across a dock, de-consolidated and re-consolidated according to final delivery location requirements.
     */
    const _464 = "464";

    /**
     * Documentary credit.
     * Document/message in which a bank states that it has issued a documentary credit under which the beneficiary is to obtain payment, acceptance or negotiation on compliance with certain terms and conditions and against presentation of stipulated documents and such drafts as may be specified. The credit may or may not be confirmed by another bank.
     */
    const _465 = "465";

    /**
     * Documentary credit notification.
     * Document/message issued by an advising bank in order to transmit a documentary credit to a beneficiary, or to another advising bank.
     */
    const _466 = "466";

    /**
     * Documentary credit transfer advice.
     * Document/message whereby a bank advises that (part of) a documentary credit is being or has been transferred in favour of a second beneficiary.
     */
    const _467 = "467";

    /**
     * Documentary credit amendment notification.
     * Document/message whereby a bank advises that the terms and conditions of a documentary credit have been amended.
     */
    const _468 = "468";

    /**
     * Documentary credit amendment.
     * Document/message whereby a bank notifies a beneficiary of the details of an amendment to the terms and conditions of a documentary credit.
     */
    const _469 = "469";

    /**
     * Waste disposal report.
     * Document/message sent by a shipping agent to an authority for reporting information on waste disposal.
     */
    const _470 = "470";

    /**
     * Remittance advice.
     * Document/message advising of the remittance of payment.
     */
    const _481 = "481";

    /**
     * Port authority waste disposal report.
     * Document/message sent by a port authority to another port authority for reporting information on waste disposal.
     */
    const _482 = "482";

    /**
     * Visa.
     * An endorsement on a passport or any other recognised travel document indicating that it has been examined and found correct, especially as permitting the holder to enter or leave a country.
     */
    const _483 = "483";

    /**
     * Multiple direct debit request.
     * Document/message containing a direct debit request to credit one or more accounts and to debit one or more debtors.
     */
    const _484 = "484";

    /**
     * Banker's draft.
     * Draft drawn in favour of a third party either by one bank on another bank, or by a branch of a bank on its head office (or vice versa) or upon another branch of the same bank. In either case, the draft should comply with the specifications laid down for cheques in the country in which it is to be payable.
     */
    const _485 = "485";

    /**
     * Multiple direct debit.
     * Document/message containing a direct debit to credit one or more accounts and to debit one or more debtors.
     */
    const _486 = "486";

    /**
     * Certificate of disembarkation permission.
     * Document or message issuing permission to disembark.
     */
    const _487 = "487";

    /**
     * Deratting exemption certificate.
     * Document certifying that the object was free of rats when inspected and that it is exempt from a deratting statement.
     */
    const _488 = "488";

    /**
     * Reefer connection order.
     * Order to connect a reefer container to a reefer point.
     */
    const _489 = "489";

    /**
     * Bill of exchange.
     * Document/message, issued and signed in conformity with the applicable legislation, which contains an unconditional order whereby the drawer directs the drawee to pay a definite sum of money to the payee or to his order, on demand or at a definite time, against the surrender of the document itself.
     */
    const _490 = "490";

    /**
     * Promissory note.
     * Document/message, issued and signed in conformity with the applicable legislation, which contains an unconditional promise whereby the maker undertakes to pay a definite sum of money to the payee or to his order, on demand or at a definite time, against the surrender of the document itself.
     */
    const _491 = "491";

    /**
     * Statement of account message.
     * Usage of STATAC-message.
     */
    const _493 = "493";

    /**
     * Direct delivery (transport).
     * Document/message ordering the direct delivery of goods/consignment from one means of transport into another means of transport in one movement.
     */
    const _494 = "494";

    /**
     * WCO second step of two-step export declaration.
     * Second part of a simplified declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for Customs export procedure based on the 1999 Kyoto Convention.
     */
    const _495 = "495";

    /**
     * WCO one-step import declaration.
     * Single step declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for Customs import procedure based on the 1999 Kyoto Convention.
     */
    const _496 = "496";

    /**
     * WCO first step of two-step import declaration.
     * First part of a simplified declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for Customs import procedure based on the 1999 Kyoto Convention.
     */
    const _497 = "497";

    /**
     * WCO second step of two-step import declaration.
     * Second part of a simplified declaration, in accordance with the WCO Customs Data Model, to Customs by which goods are declared for Customs import procedure based on the 1999 Kyoto Convention.
     */
    const _498 = "498";

    /**
     * Previous transport document.
     * Identification of the previous transport document.
     */
    const _499 = "499";

    /**
     * Insurance certificate.
     * Document/message issued to the insured certifying that insurance has been effected and that a policy has been issued. Such a certificate for a particular cargo is primarily used when good are insured under the terms of a floating or an open policy; at the request of the insured it can be exchanged for a policy.
     */
    const _520 = "520";

    /**
     * Special requirements permit related to the transport of
     * cargo A permit related to a transport document granting the transport of cargo under the conditions as specifically required.
     */
    const _521 = "521";

    /**
     * Dangerous Goods Notification for Tanker vessel.
     * Dangerous Goods Notification for a vessel carrying liquid cargo in bulk.
     */
    const _522 = "522";

    /**
     * Dangerous Goods Notification for non-tanker vessel.
     * Dangerous Goods Notification for a vessel carrying cargo other than bulk liquid cargo.
     */
    const _523 = "523";

    /**
     * WCO Conveyance Arrival Report.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs regarding the conveyance arriving in a Customs territory.
     */
    const _524 = "524";

    /**
     * WCO Conveyance Departure Report.
     * Declaration, in accordance with the WCO Customs Data Model, to Customs regarding the conveyance departing a Customs territory.
     */
    const _525 = "525";

    /**
     * Accounting voucher.
     * A document/message justifying an accounting entry.
     */
    const _526 = "526";

    /**
     * Self billed debit note.
     * A document which indicates that the customer is claiming debit in a self billing environment.
     */
    const _527 = "527";

    /**
     * Military Identification Card.
     * The official document used for military personnel on travel orders, substituting a passport.
     */
    const _528 = "528";

    /**
     * Re-Entry Permit.
     * A permit to re-enter a country.
     */
    const _529 = "529";

    /**
     * Insurance policy.
     * Document/message issued by the insurer evidencing an agreement to insure and containing the conditions of the agreement concluded whereby the insurer undertakes for a specific fee to indemnify the insured for the losses arising out of the perils and accidents specified in the contract.
     */
    const _530 = "530";

    /**
     * Refugee Permit.
     * Document identifying a refugee recognized by a country.
     */
    const _531 = "531";

    /**
     * Forwarder's credit note.
     * Document/message for providing credit information to the relevant party.
     */
    const _532 = "532";

    /**
     * Original accounting voucher.
     * To indicate that the document/message justifying an accounting entry is original.
     */
    const _533 = "533";

    /**
     * Copy accounting voucher.
     * To indicate that the document/message justifying an accounting entry is a copy.
     */
    const _534 = "534";

    /**
     * Pro-forma accounting voucher.
     * To indicate that the document/message justifying an accounting entry is pro-forma.
     */
    const _535 = "535";

    /**
     * International Ship Security Certificate.
     * A certificate on ship security issued based on the International code for the Security of Ships and of Port facilities (ISPS code).
     */
    const _536 = "536";

    /**
     * Interim International Ship Security Certificate.
     * An interim certificate on ship security issued basis under the International code for the Security of Ships and of Port facilities (ISPS code).
     */
    const _537 = "537";

    /**
     * Good Manufacturing Practice (GMP) Certificate.
     * Certificate that guarantees quality manufacturing and processing of food products, medications, cosmetics, etc.
     */
    const _538 = "538";

    /**
     * Framework Agreement.
     * An agreement between one or more contracting authorities and one or more economic operators, the purpose of which is to establish the terms governing contracts to be awarded during a given period, in particular with regard to price and, where appropriate, the quantity envisaged.
     */
    const _539 = "539";

    /**
     * Insurance declaration sheet (bordereau).
     * A document/message used when an insured reports to his insurer details of individual shipments which are covered by an insurance contract - an open cover or a floating policy - between the parties.
     */
    const _550 = "550";

    /**
     * Transport capacity offer.
     * Offering of capacity for the transport of goods for a date and a route.
     */
    const _551 = "551";

    /**
     * Ship Security Plan.
     * Ship Security Plan (SSP) is a document prepared in terms of the ISPS Code to contribute to the prevention of illegal acts against the ship and its crew.
     */
    const _552 = "552";

    /**
     * Forwarder's invoice discrepancy report.
     * Document/message reporting invoice discrepancies indentified by the forwarder.
     */
    const _553 = "553";

    /**
     * Storage capacity offer.
     * Offering of capacity to store goods.
     */
    const _554 = "554";

    /**
     * Insurer's invoice.
     * Document/message issued by an insurer specifying the cost of an insurance which has been effected and claiming payment therefore.
     */
    const _575 = "575";

    /**
     * Storage capacity request.
     * Request for capacity to store goods.
     */
    const _576 = "576";

    /**
     * Transport capacity request.
     * Request for capacity for the transport of goods for a date and a route.
     */
    const _577 = "577";

    /**
     * EU Customs declaration for External Community Transit (T1).
     * Customs declaration for goods under the external Community/common transit procedure. This applies to "non-Community goods" ("T1" under EU legislation and EC- EFTA "Transit Convention").
     */
    const _578 = "578";

    /**
     * EU Customs declaration for internal Community Transit (T2).
     * Customs declaration for goods under the internal Community/common transit procedure. This applies to "Community goods" ("T2" under EU legislation and EC-EFTA "Transit Convention").
     */
    const _579 = "579";

    /**
     * Cover note.
     * Document/message issued by an insurer (insurance broker, agent, etc.) to notify the insured that his insurance have been carried out.
     */
    const _580 = "580";

    /**
     * EU Customs declaration for non-fiscal area internal.
     * Community Transit (T2F) Declaration for goods under the internal Community transit procedure in the context of trade between the "VAT" territory of EU Member States and EU territories where the VAT rules do not apply, such as Canary islands, some French overseas territories, the Channel islands and the Aaland islands, and between those territories. ("T2F" under EU Legislation).
     */
    const _581 = "581";

    /**
     * EU Customs declaration for internal transit to San Marino.
     * (T2SM) Customs declaration for goods under the internal Community transit procedure between the Community and San Marino. ("T2SM" under EU Legislation).
     */
    const _582 = "582";

    /**
     * EU Customs declaration for mixed consignments (T).
     * Customs declaration for goods under the Community/common transit procedure for mixed consignments (i.e. consignments that comprise goods of different statuses, like "T1" and "T2") ("T" under EU Legislation).
     */
    const _583 = "583";

    /**
     * EU Document for establishing the Community status of goods.
     * (T2L) Form establishing the Community status of goods ("T2L" under EU Legislation).
     */
    const _584 = "584";

    /**
     * EU Document for establishing the Community status of goods
     * for certain fiscal purposes (T2LF) Form establishing the Community status of goods in the context of trade between the "VAT" territory of EU Member States and EU territories where the VAT rules do not apply, such as Canary islands, some French overseas territories, the Channel islands and the Aaland islands, and between those territories ("T2LF" under EU Legislation).
     */
    const _585 = "585";

    /**
     * Document for establishing the Customs Status of goods for
     * San Marino (T2LSM) Form establishing the Community status of goods ("T2L" under European Legislation) in the context of trade between the EU and San Marino. ("T2LSM" under EU Legislation).
     */
    const _586 = "586";

    /**
     * Customs declaration for TIR Carnet goods.
     * A Customs declaration in which goods move under cover of TIR Carnets.
     */
    const _587 = "587";

    /**
     * Transport Means Security Report.
     * A document reporting the security status and related information of a means of transport.
     */
    const _588 = "588";

    /**
     * Halal Slaughtering Certificate.
     * A certificate verifying that meat has been produced from slaughter in accordance with Islamic laws and practices.
     */
    const _589 = "589";

    /**
     * Forwarding instructions.
     * Document/message issued to a freight forwarder, giving instructions regarding the action to be taken by the forwarder for the forwarding of goods described therein.
     */
    const _610 = "610";

    /**
     * Forwarder's advice to import agent.
     * Document/message issued by a freight forwarder in an exporting country advising his counterpart in an importing country about the forwarding of goods described therein.
     */
    const _621 = "621";

    /**
     * Forwarder's advice to exporter.
     * Document/message issued by a freight forwarder informing an exporter of the action taken in fulfillment of instructions received.
     */
    const _622 = "622";

    /**
     * Forwarder's invoice.
     * Invoice issued by a freight forwarder specifying services rendered and costs incurred and claiming payment therefore.
     */
    const _623 = "623";

    /**
     * Forwarder's certificate of receipt.
     * Non-negotiable document issued by a forwarder to certify that he has assumed control of a specified consignment, with irrevocable instructions to send it to the consignee indicated in the document or to hold it at his disposal. E.g. FIATA-FCR.
     */
    const _624 = "624";

    /**
     * Heat Treatment Certificate.
     * A certificate verifying the heat treatment of the product is in conformance with international standards to ensure the product's healthiness and/or shows the mode of heat treatment indicating the temperature and the amount of time the product or raw material used in the product was treated (such as milk).
     */
    const _625 = "625";

    /**
     * Convention on International Trade in Endangered Species of
     * Wild Fauna and Flora (CITES) Certificate A certificate used in the trade of endangered species in accordance with the CITES convention.
     */
    const _626 = "626";

    /**
     * Free Sale Certificate in the Country of Origin.
     * A certificate confirming that a specified product is free for sale in the country of origin.
     */
    const _627 = "627";

    /**
     * Transit license.
     * Document/message issued by the competent body in accordance with transit regulations in force, by which authorization is granted to a party to move articles under customs procedure.
     */
    const _628 = "628";

    /**
     * Veterinary quarantine certificate.
     * A certification that livestock or animal products, that are either imported or entering free zones, are kept under health supervision for a time period determined by veterinary quarantine instructions.
     */
    const _629 = "629";

    /**
     * Shipping note.
     * (1123) Document/message provided by the shipper or his agent to the carrier, multimodal transport operator, terminal or other receiving authority, giving information about export consignments offered for transport, and providing for the necessary receipts and declarations of liability. Sometimes a multipurpose cargo handling document also fulfilling the functions of document 632, 633, 650 and 655.
     */
    const _630 = "630";

    /**
     * Forwarder's warehouse receipt.
     * Document/message issued by a forwarder acting as Warehouse Keeper acknowledging receipt of goods placed in a warehouse, and stating or referring to the conditions which govern the warehousing and the release of goods. The document contains detailed provisions regarding the rights of holders-by-endorsement, transfer of ownership, etc. E.g. FIATA-FWR.
     */
    const _631 = "631";

    /**
     * Goods receipt.
     * Document/message to acknowledge the receipt of goods and in addition may indicate receiving conditions.
     */
    const _632 = "632";

    /**
     * Port charges documents.
     * Documents/messages specifying services rendered, storage and handling costs, demurrage and other charges due to the owner of goods described therein.
     */
    const _633 = "633";

    /**
     * Certified list of ingredients.
     * A document legalized from a competent authority that shows the components of the product (food additive, detergent, disinfectant and sanitizer).
     */
    const _634 = "634";

    /**
     * Warehouse warrant.
     * Negotiable receipt document, issued by a Warehouse Keeper to a person placing goods in a warehouse and conferring title to the goods stored.
     */
    const _635 = "635";

    /**
     * Health certificate.
     * A document legalized from a competent authority that shows that the product has been tested microbiologically and is free from any pathogens and fit for human consumption and/or declares that the product is in compliance with sanitary and phytosanitary measures.
     */
    const _636 = "636";

    /**
     * Food grade certificate.
     * A document that shows that the product (food additive, detergent, disinfectant and sanitizer) is suitable to be used in the food industry.
     */
    const _637 = "637";

    /**
     * Certificate of suitability for transport of grains and
     * legumes Certificate of inspection for the vessel stating its readiness and suitability for transporting grains and legumes.
     */
    const _638 = "638";

    /**
     * Certificate of refrigerated transport equipment inspection.
     * Inspection document shows that the container, the cooling devices and measured temperature is in good working condition.
     */
    const _639 = "639";

    /**
     * Delivery order.
     * Document/message issued by a party entitled to authorize the release of goods specified therein to a named consignee, to be retained by the custodian of the goods.
     */
    const _640 = "640";

    /**
     * Thermographic reading report.
     * A report of temperature readings over a period.
     */
    const _641 = "641";

    /**
     * Certificate of food item transport readiness.
     * A certificate to verify readiness of a transport or transport area such as a reservoir or hold to transport food items.
     */
    const _642 = "642";

    /**
     * Food packaging contact certificate.
     * A document legalized from a competent authority that shows that the food packaging product is safe to come into contact with food.
     */
    const _643 = "643";

    /**
     * Packaging material composition report.
     * A document that shows the main structure that composes the packaging material.
     */
    const _644 = "644";

    /**
     * Export price certificate.
     * A certification executed by the competent authority from country of exportation stating the export price of the goods.
     */
    const _645 = "645";

    /**
     * Public price certificate.
     * A certification executed by the competent authority from country of production stating the price of the goods to the general public.
     */
    const _646 = "646";

    /**
     * Drug shelf life study report.
     * A document containing results from the study which determines the shelf life, namely the time period of storage at a specified condition within which a drug substance or drug product still meets its established specifications; its identity, strength, quality and purity.
     */
    const _647 = "647";

    /**
     * Certificate of compliance with standards of the World.
     * Organization for Animal Health (OIE) A certification that the products have been treated in a way consistent with the standards set by the World Organization for Animal Health (OIE).
     */
    const _648 = "648";

    /**
     * Production facility license.
     * A license granted by a competent authority to a production facility for manufacturing specific products.
     */
    const _649 = "649";

    /**
     * Handling order.
     * Document/message issued by a cargo handling organization (port administration, terminal operator, etc.) for the removal or other handling of goods under their care.
     */
    const _650 = "650";

    /**
     * Manufacturing license.
     * A license granted by a competent authority to a manufacturer for production of specific products.
     */
    const _651 = "651";

    /**
     * Low risk country formal letter.
     * An official letter issued by an import authority granted to the importer of goods from a low risk country which allows the importer to place its products in the local market with certain favorable considerations.
     */
    const _652 = "652";

    /**
     * Previous correspondence.
     * Correspondence previously exchanged.
     */
    const _653 = "653";

    /**
     * Declaration for radioactive material.
     * A declaration to be presented to the competent authority when radioactive material moves cross-border.
     */
    const _654 = "654";

    /**
     * Gate pass.
     * Document/message authorizing goods specified therein to be brought out of a fenced-in port or terminal area.
     */
    const _655 = "655";

    /**
     * Resale information.
     * Document/message providing information on a resale.
     */
    const _656 = "656";

    /**
     * Phytosanitary Re-export Certificate.
     * A message/document consistent with the model for re- export phytosanitary certificates of the IPPC, attesting that a consignment meets phytosanitary import requirements.
     */
    const _657 = "657";

    /**
     * Bayplan/stowage plan, full.
     * A full bayplan containing all occupied and/or blocked stowage locations.
     */
    const _658 = "658";

    /**
     * Bayplan/stowage plan, partial.
     * A partial bayplan. containing only a selected part of the available stowage locations.
     */
    const _659 = "659";

    /**
     * Waybill.
     * Non-negotiable document evidencing the contract for the transport of cargo.
     */
    const _700 = "700";

    /**
     * Universal (multipurpose) transport document.
     * Document/message evidencing a contract of carriage covering the movement of goods by any mode of transport, or combination of modes, for national as well as international transport, under any applicable international convention or national law and under the conditions of carriage of any carrier or transport operator undertaking or arranging the transport referred to in the document.
     */
    const _701 = "701";

    /**
     * Goods receipt, carriage.
     * Document/message issued by a carrier or a carrier's agent, acknowledging receipt for carriage of goods specified therein on conditions stated or referred to in the document, enabling the carrier to issue a transport document.
     */
    const _702 = "702";

    /**
     * House waybill.
     * The document made out by an agent/consolidator which evidences the contract between the shipper and the agent/consolidator for the arrangement of carriage of goods.
     */
    const _703 = "703";

    /**
     * Master bill of lading.
     * A bill of lading issued by the master of a vessel (in actuality the owner or charterer of the vessel). It could cover a number of house bills.
     */
    const _704 = "704";

    /**
     * Bill of lading.
     * Negotiable document/message which evidences a contract of carriage by sea and the taking over or loading of goods by carrier, and by which carrier undertakes to deliver goods against surrender of the document. A provision in the document that goods are to be delivered to the order of a named person, or to order, or to bearer, constitutes such an undertaking.
     */
    const _705 = "705";

    /**
     * Bill of lading original.
     * The original of the bill of lading issued by a transport company. When issued by the maritime industry it could signify ownership of the cargo.
     */
    const _706 = "706";

    /**
     * Bill of lading copy.
     * A copy of the bill of lading issued by a transport company.
     */
    const _707 = "707";

    /**
     * Empty container bill.
     * Bill of lading indicating an empty container.
     */
    const _708 = "708";

    /**
     * Tanker bill of lading.
     * Document which evidences a transport of liquid bulk cargo.
     */
    const _709 = "709";

    /**
     * Sea waybill.
     * Non-negotiable document which evidences a contract for the carriage of goods by sea and the taking over of the goods by the carrier, and by which the carrier undertakes to deliver the goods to the consignee named in the document.
     */
    const _710 = "710";

    /**
     * Inland waterway bill of lading.
     * Negotiable transport document made out to a named person, to order or to bearer, signed by the carrier and handed to the sender after receipt of the goods.
     */
    const _711 = "711";

    /**
     * Non-negotiable maritime transport document (generic).
     * Non-negotiable document which evidences a contract for the carriage of goods by sea and the taking over or loading of the goods by the carrier, and by which the carrier undertakes to deliver the goods to the consignee named in the document. E.g. Sea waybill. Remark: Synonymous with "straight" or "non-negotiable Bill of lading" used in certain countries, e.g. Canada.
     */
    const _712 = "712";

    /**
     * Mate's receipt.
     * Document/message issued by a ship's officer to acknowledge that a specified consignment has been received on board a vessel, and the apparent condition of the goods; enabling the carrier to issue a Bill of lading.
     */
    const _713 = "713";

    /**
     * House bill of lading.
     * The bill of lading issued not by the carrier but by the freight forwarder/consolidator known by the carrier.
     */
    const _714 = "714";

    /**
     * Letter of indemnity for non-surrender of bill of lading.
     * Document/message issued by a commercial party or a bank of an insurance company accepting responsibility to the beneficiary of the indemnity in accordance with the terms thereof.
     */
    const _715 = "715";

    /**
     * Forwarder's bill of lading.
     * Non-negotiable document issued by a freight forwarder evidencing a contract for the carriage of goods by sea and the taking over or loading of the goods by the freight forwarder, and by which the freight forwarder undertakes to deliver the goods to the consignee named in the document.
     */
    const _716 = "716";

    /**
     * Residence permit.
     * A document authorizing residence.
     */
    const _717 = "717";

    /**
     * Seaman's book.
     * A national identity document issued to professional seamen that contains a record of their rank and service career.
     */
    const _718 = "718";

    /**
     * General message.
     * Document/message providing agreed textual information.
     */
    const _719 = "719";

    /**
     * Rail consignment note (generic term).
     * Transport document constituting a contract for the carriage of goods between the sender and the carrier (the railway). For international rail traffic, this document must conform to the model prescribed by the international conventions concerning carriage of goods by rail, e.g. CIM Convention, SMGS Convention.
     */
    const _720 = "720";

    /**
     * Product data response.
     * Document/message responding to a previously received Product Data document/message.
     */
    const _721 = "721";

    /**
     * Road list-SMGS.
     * Accounting document, one copy of which is drawn up for each consignment note; it accompanies the consignment over the whole route and is a rail transport document.
     */
    const _722 = "722";

    /**
     * Escort official recognition.
     * Document/message which gives right to the owner to exert all functions normally transferred to a guard in a train by which an escorted consignment is transported.
     */
    const _723 = "723";

    /**
     * Recharging document.
     * Fictitious transport document regarding a previous transport, enabling a carrier's agent to give to another carrier's agent (in a different country) the possibility to collect charges relating to the original transport (rail environment).
     */
    const _724 = "724";

    /**
     * Manufacturer raised order.
     * Document/message providing details of an order which has been raised by a manufacturer.
     */
    const _725 = "725";

    /**
     * Manufacturer raised consignment order.
     * Document/message providing details of a consignment order which has been raised by a manufacturer.
     */
    const _726 = "726";

    /**
     * Price/sales catalogue not containing commercial information.
     * A price/sales catalogue message containing no commercial information, such as prices, terms or conditions.
     */
    const _727 = "727";

    /**
     * Price/sales catalogue containing commercial information.
     * A price/sales catalogue message containing only commercial terms or conditions data.
     */
    const _728 = "728";

    /**
     * Returns advice.
     * Document/message by means of which the buyer informs the seller about the despatch of returned goods.
     */
    const _729 = "729";

    /**
     * Road consignment note.
     * Transport document/message which evidences a contract between a carrier and a sender for the carriage of goods by road (generic term). Remark: For international road traffic, this document must contain at least the particulars prescribed by the convention on the contract for the international carriage of goods by road (CMR).
     */
    const _730 = "730";

    /**
     * Commercial account summary.
     * A message enabling the transmission of commercial data concerning payments made and outstanding items on an account over a period of time.
     */
    const _731 = "731";

    /**
     * Announcement for returns.
     * A message by which a party announces to another party details of goods for return due to specified reasons (e.g. returns for repair, returns because of damage, etc).
     */
    const _732 = "732";

    /**
     * Instruction for returns.
     * A message by which a party informs another party whether and how goods shall be returned.
     */
    const _733 = "733";

    /**
     * Sales forecast report.
     * A message enabling companies to exchange or report electronically, basic sales forecast data related to products or services, including the corresponding location, time period, product identification, pricing and quantity information. It enables the recip.
     */
    const _734 = "734";

    /**
     * Sales data report.
     * A message enabling companies to exchange or report electronically, basic sales data related to products or services, including the corresponding location, time period, product identification, pricing and quantity information. It enables the recipient to p.
     */
    const _735 = "735";

    /**
     * Standing inquiry on complete product information.
     * A product inquiry which stands until it is cancelled. It requests not only the updates since last time, but always the complete product information of a data supplier. This means that within the standing request every time a complete download of the respe.
     */
    const _736 = "736";

    /**
     * Proof of delivery.
     * A message by which a consignee provides for a carrier proof of delivery of a consignment.
     */
    const _737 = "737";

    /**
     * Cargo/goods handling and movement message.
     * A message from a party to a warehouse, distribution centre, or logistics service provider identifying the handling services and where required the movement of specified goods, limited to warehouses within the jurisdiction of the distribution centre or log.
     */
    const _738 = "738";

    /**
     * Metered services consumption report supporting an invoice.
     * Document/message providing metered consumption details supporiting an invoice.
     */
    const _739 = "739";

    /**
     * Air waybill.
     * Document/message made out by or on behalf of the shipper which evidences the contract between the shipper and carrier(s) for carriage of goods over routes of the carrier(s) and which is identified by the airline prefix issuing the document plus a serial (IATA).
     */
    const _740 = "740";

    /**
     * Master air waybill.
     * Document/message made out by or on behalf of the agent/consolidator which evidences the contract between the agent/consolidator and carrier(s) for carriage of goods over routes of the carrier(s) for a consignment consisting of goods originated by more than one shipper (IATA).
     */
    const _741 = "741";

    /**
     * Metered services consumption report.
     * Document/message providing metered consumption details.
     */
    const _742 = "742";

    /**
     * Substitute air waybill.
     * A temporary air waybill which contains only limited information because of the absence of the original.
     */
    const _743 = "743";

    /**
     * Crew's effects declaration.
     * Declaration to Customs regarding the personal effects of crew members aboard the conveyance; equivalent to IMO FAL 4.
     */
    const _744 = "744";

    /**
     * Passenger list.
     * Declaration to Customs regarding passengers aboard the conveyance; equivalent to IMO FAL 6.
     */
    const _745 = "745";

    /**
     * Delivery notice (rail transport).
     * Document/message created by the consignor or by the departure station, joined to the transport or sent to the consignee, giving the possibility to the consignee or the arrival station to attest the delivery of the goods. The document must be returned to the consignor or to the departure station.
     */
    const _746 = "746";

    /**
     * Payroll deductions advice.
     * A message sent by a party (usually an employer or its representative) to a service providing organisation, to detail payroll deductions paid on behalf of its employees to the service providing organisation.
     */
    const _747 = "747";

    /**
     * Consignment despatch advice.
     * Document/message by means of which the supplier informs the buyer about the despatch of goods ordered on consignment (goods to be delivered into stock with agreement on payment when goods are sold out of this stock).
     */
    const _748 = "748";

    /**
     * Transport equipment gross mass verification message.
     * Message containing information regarding gross mass verification of transport equipment.
     */
    const _749 = "749";

    /**
     * Despatch note (post parcels).
     * Document/message which, according to Article 106 of the "Agreement concerning Postal Parcels" under the UPU convention, is to accompany post parcels.
     */
    const _750 = "750";

    /**
     * Invoice information for accounting purposes.
     * A document / message containing accounting related information such as monetary summations, seller id and VAT information. This may not be a complete invoice according to legal requirements. For instance the line item information might be excluded.
     */
    const _751 = "751";

    /**
     * Multimodal/combined transport document (generic).
     * A transport document used when more than one mode of transportation is involved in the movement of cargo. It is a contract of carriage and receipt of the cargo for a multimodal transport. It indicates the place where the responsible transport company in the move takes responsibility for the cargo, the place where the responsibility of this transport company in the move ends and the conveyances involved.
     */
    const _760 = "760";

    /**
     * Through bill of lading.
     * Bill of lading which evidences a contract of carriage from one place to another in separate stages of which at least one stage is a sea transit, and by which the issuing carrier accepts responsibility for the carriage as set forth in the through bill of lading.
     */
    const _761 = "761";

    /**
     * Forwarder's certificate of transport.
     * Negotiable document/message issued by a forwarder to certify that he has taken charge of a specified consignment for despatch and delivery in accordance with the consignor's instructions, as indicated in the document, and that he accepts responsibility for delivery of the goods to the holder of the document through the intermediary of a delivery agent of his choice. E.g. FIATA-FCT.
     */
    const _763 = "763";

    /**
     * Combined transport document (generic).
     * Negotiable or non-negotiable document evidencing a contract for the performance and/or procurement of performance of combined transport of goods and bearing on its face either the heading "Negotiable combined transport document issued subject to Uniform Rules for a Combined Transport Document (ICC Brochure No. 298)" or the heading "Non-negotiable Combined Transport Document issued subject to Uniform Rules for a Combined Transport Document (ICC Brochure No. 298)".
     */
    const _764 = "764";

    /**
     * Multimodal transport document (generic).
     * Document/message which evidences a multimodal transport contract, the taking in charge of the goods by the multimodal transport operator, and an undertaking by him to deliver the goods in accordance with the terms of the contract. (International Convention on Multimodal Transport of Goods).
     */
    const _765 = "765";

    /**
     * Combined transport bill of lading/multimodal bill of lading.
     * Document which evidences a multimodal transport contract, the taking in charge of the goods by the multimodal transport operator, and an undertaking by him to deliver the goods in accordance with the terms of the contract.
     */
    const _766 = "766";

    /**
     * Booking confirmation.
     * Document/message issued by a carrier to confirm that space has been reserved for a consignment in means of transport.
     */
    const _770 = "770";

    /**
     * Calling forward notice.
     * Instructions for release or delivery of goods.
     */
    const _775 = "775";

    /**
     * Freight invoice.
     * Document/message issued by a transport operation specifying freight costs and charges incurred for a transport operation and stating conditions of payment.
     */
    const _780 = "780";

    /**
     * Arrival notice (goods).
     * Notification from the carrier to the consignee in writing, by telephone or by any other means (express letter, message, telegram, etc.) informing him that a consignment addressed to him is being or will shortly be held at his disposal at a specified point in the place of destination.
     */
    const _781 = "781";

    /**
     * Notice of circumstances preventing delivery (goods).
     * Request made by the carrier to the sender, or, as the case may be, the consignee, for instructions as to the disposal of the consignment when circumstances prevent delivery and the return of the goods has not been requested by the consignor in the transport document.
     */
    const _782 = "782";

    /**
     * Notice of circumstances preventing transport (goods).
     * Request made by the carrier to the sender, or, the consignee as the case may be, for instructions as to the disposal of the goods when circumstances prevent transport before departure or en route, after acceptance of the consignment concerned.
     */
    const _783 = "783";

    /**
     * Delivery notice (goods).
     * Notification in writing, sent by the carrier to the sender, to inform him at his request of the actual date of delivery of the goods.
     */
    const _784 = "784";

    /**
     * Cargo manifest.
     * Listing of goods comprising the cargo carried in a means of transport or in a transport-unit. The cargo manifest gives the commercial particulars of the goods, such as transport document numbers, consignors, consignees, shipping marks, number and kind of packages and descriptions and quantities of the goods.
     */
    const _785 = "785";

    /**
     * Freight manifest.
     * Document/message containing the same information as a cargo manifest, and additional details on freight amounts, charges, etc.
     */
    const _786 = "786";

    /**
     * Bordereau.
     * Document/message used in road transport, listing the cargo carried on a road vehicle, often referring to appended copies of Road consignment note.
     */
    const _787 = "787";

    /**
     * Container manifest (unit packing list).
     * Document/message specifying the contents of particular freight containers or other transport units, prepared by the party responsible for their loading into the container or unit.
     */
    const _788 = "788";

    /**
     * Charges note.
     * Document used by the rail organization to indicate freight charges or additional charges in each case where the departure station is not able to calculate the charges for the total voyage (e.g. tariff not yet updated, part of voyage not covered by the tariff). This document must be considered as joined to the transport.
     */
    const _789 = "789";

    /**
     * Advice of collection.
     * (1030) Document that is joined to the transport or sent by separate means, giving to the departure rail organization the proof that the cash-on delivery amount has been encashed by the arrival rail organization before reimbursement of the consignor.
     */
    const _790 = "790";

    /**
     * Safety of ship certificate.
     * Document certifying a ship's safety to a specified date.
     */
    const _791 = "791";

    /**
     * Safety of radio certificate.
     * Document certifying the safety of a ship's radio facilities to a specified date.
     */
    const _792 = "792";

    /**
     * Safety of equipment certificate.
     * Document certifying the safety of a ship's equipment to a specified date.
     */
    const _793 = "793";

    /**
     * Civil liability for oil certificate.
     * Document declaring a ship owner's liability for oil propelling or carried on a vessel.
     */
    const _794 = "794";

    /**
     * Loadline document.
     * Document specifying the limit of a ship's legal submersion under various conditions.
     */
    const _795 = "795";

    /**
     * Derat document.
     * Document certifying that a ship is free of rats, valid to a specified date.
     */
    const _796 = "796";

    /**
     * Maritime declaration of health.
     * Document certifying the health condition on board a vessel, valid to a specified date.
     */
    const _797 = "797";

    /**
     * Certificate of registry.
     * Official certificate stating the vessel's registry.
     */
    const _798 = "798";

    /**
     * Ship's stores declaration.
     * Declaration to Customs regarding the contents of the ship's stores (equivalent to IMO FAL 3) i.e. goods intended for consumption by passengers/crew on board vessels, aircraft or trains, whether or not sold or landed; goods necessary for operation/maintenance of conveyance, including fuel/lubricants, excluding spare parts/equipment (IMO).
     */
    const _799 = "799";

    /**
     * Export licence, application for.
     * Application for a permit issued by a government authority permitting exportation of a specified commodity subject to specified conditions as quantity, country of destination, etc.
     */
    const _810 = "810";

    /**
     * Export licence.
     * Permit issued by a government authority permitting exportation of a specified commodity subject to specified conditions as quantity, country of destination, etc. Synonym: Embargo permit.
     */
    const _811 = "811";

    /**
     * Exchange control declaration, export.
     * Document/message completed by an exporter/seller as a means whereby the competent body may control that the amount of foreign exchange accrued from a trade transaction is repatriated in accordance with the conditions of payment and exchange control regulations in force.
     */
    const _812 = "812";

    /**
     * Despatch note model T.
     * European community transit declaration.
     */
    const _820 = "820";

    /**
     * Despatch note model T1.
     * Transit declaration for goods circulating under internal community transit procedures (between European Union (EU) countries).
     */
    const _821 = "821";

    /**
     * Despatch note model T2.
     * Ascertainment that the declared goods were originally produced in an European Union (EU) country.
     */
    const _822 = "822";

    /**
     * Control document T5.
     * Control document (export declaration) used particularly in case of re-sending without use with only VAT collection, refusal, unconformity with contract etc.
     */
    const _823 = "823";

    /**
     * Re-sending consignment note.
     * Rail consignment note prepared by the consignor for the facilitation of an eventual return to the origin of the goods.
     */
    const _824 = "824";

    /**
     * Despatch note model T2L.
     * Ascertainment that the declared goods were originally produced in an European Union (EU) country. May only be used for goods that are loaded on one single means of transport in one single departure point for one single delivery point.
     */
    const _825 = "825";

    /**
     * Goods declaration for exportation.
     * Document/message by which goods are declared for export Customs clearance, conforming to the layout key set out at Appendix I to Annex C.1 concerning outright exportation to the Kyoto convention (CCC). Within a Customs union, "for despatch" may have the same meaning as "for exportation".
     */
    const _830 = "830";

    /**
     * Cargo declaration (departure).
     * Generic term, sometimes referred to as Freight declaration, applied to the documents providing the particulars required by the Customs concerning the cargo (freight) carried by commercial means of transport (CCC).
     */
    const _833 = "833";

    /**
     * Application for goods control certificate.
     * Document/message submitted to a competent body by party requesting a Goods control certificate to be issued in accordance with national or international standards, or conforming to legislation in the importing country, or as specified in the contract.
     */
    const _840 = "840";

    /**
     * Goods control certificate.
     * Document/message issued by a competent body evidencing the quality of the goods described therein, in accordance with national or international standards, or conforming to legislation in the importing country, or as specified in the contract.
     */
    const _841 = "841";

    /**
     * Application for phytosanitary certificate.
     * Document/message submitted to a competent body by party requesting a Phytosanitary certificate to be issued.
     */
    const _850 = "850";

    /**
     * Phytosanitary certificate.
     * A message/doucment consistent with the model for certificates of the IPPC, attesting that a consignment meets phytosanitary import requirements.
     */
    const _851 = "851";

    /**
     * Sanitary certificate.
     * Document/message issued by the competent authority in the exporting country evidencing that alimentary and animal products, including dead animals, are fit for human consumption, and giving details, when relevant, of controls undertaken.
     */
    const _852 = "852";

    /**
     * Veterinary certificate.
     * Document/message issued by the competent authority in the exporting country evidencing that live animals or birds are not infested or infected with disease, and giving details regarding their provenance, and of vaccinations and other treatment to which they have been subjected.
     */
    const _853 = "853";

    /**
     * Application for inspection certificate.
     * Document/message submitted to a competent body by a party requesting an Inspection certificate to be issued in accordance with national or international standards, or conforming to legislation in the country in which it is required, or as specified in the contract.
     */
    const _855 = "855";

    /**
     * Inspection certificate.
     * Document/message issued by a competent body evidencing that the goods described therein have been inspected in accordance with national or international standards, in conformity with legislation in the country in which the inspection is required, or as specified in the contract.
     */
    const _856 = "856";

    /**
     * Certificate of origin, application for.
     * Document/message submitted to a competent body by an interested party requesting a Certificate of origin to be issued in accordance with relevant criteria, and on the basis of evidence of the origin of the goods.
     */
    const _860 = "860";

    /**
     * Certificate of origin.
     * Document/message identifying goods, in which the authority or body authorized to issue it certifies expressly that the goods to which the certificate relates originate in a specific country. The word "country" may include a group of countries, a region or a part of a country. This certificate may also include a declaration by the manufacturer, producer, supplier, exporter or other competent person.
     */
    const _861 = "861";

    /**
     * Declaration of origin.
     * Appropriate statement as to the origin of the goods, made in connection with their exportation by the manufacturer, producer, supplier, exporter or other competent person on the Commercial invoice or any other document relating to the goods (CCC).
     */
    const _862 = "862";

    /**
     * Regional appellation certificate.
     * Certificate drawn up in accordance with the rules laid down by an authority or approved body, certifying that the goods described therein qualify for a designation specific to the given region (e.g. champagne, port wine, Parmesan cheese).
     */
    const _863 = "863";

    /**
     * Preference certificate of origin.
     * Document/message describing a certificate of origin meeting the requirements for preferential treatment.
     */
    const _864 = "864";

    /**
     * Certificate of origin form GSP.
     * Specific form of certificate of origin for goods qualifying for preferential treatment under the generalized system of preferences (includes a combined declaration of origin and certificate, form A).
     */
    const _865 = "865";

    /**
     * Consular invoice.
     * Document/message to be prepared by an exporter in his country and presented to a diplomatic representation of the importing country for endorsement and subsequently to be presented by the importer in connection with the import of the goods described therein.
     */
    const _870 = "870";

    /**
     * Dangerous goods declaration.
     * (1115) Document/message issued by a consignor in accordance with applicable conventions or regulations, describing hazardous goods or materials for transport purposes, and stating that the latter have been packed and labelled in accordance with the provisions of the relevant conventions or regulations.
     */
    const _890 = "890";

    /**
     * Statistical document, export.
     * Document/message in which an exporter provides information about exported goods required by the body responsible for the collection of international trade statistics.
     */
    const _895 = "895";

    /**
     * INTRASTAT declaration.
     * Document/message in which a declarant provides information about goods required by the body responsible for the collection of trade statistics.
     */
    const _896 = "896";

    /**
     * Delivery verification certificate.
     * Document/message whereby an official authority (Customs or governmental) certifies that goods have been delivered.
     */
    const _901 = "901";

    /**
     * Import licence, application for.
     * Document/message in which an interested party applies to the competent body for authorization to import either a limited quantity of articles subject to import restrictions, or an unlimited quantity of such articles during a limited period, and specifies the kind of articles, their origin and value, etc.
     */
    const _910 = "910";

    /**
     * Import licence.
     * Document/message issued by the competent body in accordance with import regulations in force, by which authorization is granted to a named party to import either a limited quantity of designated articles or an unlimited quantity of such articles during a limited period, under conditions specified in the document.
     */
    const _911 = "911";

    /**
     * Customs declaration without commercial detail.
     * CUSDEC transmission that does not include data from the commercial detail section of the message.
     */
    const _913 = "913";

    /**
     * Customs declaration with commercial and item detail.
     * CUSDEC transmission that includes data from both the commercial detail and item detail sections of the message.
     */
    const _914 = "914";

    /**
     * Customs declaration without item detail.
     * CUSDEC transmission that does not include data from the item detail section of the message.
     */
    const _915 = "915";

    /**
     * Related document.
     * Document that has a relationship with the stated document/message.
     */
    const _916 = "916";

    /**
     * Receipt (Customs).
     * Receipt for Customs duty/tax/fee paid.
     */
    const _917 = "917";

    /**
     * Application for exchange allocation.
     * Document/message whereby an importer/buyer requests the competent body to allocate an amount of foreign exchange to be transferred to an exporter/seller in payment for goods.
     */
    const _925 = "925";

    /**
     * Foreign exchange permit.
     * Document/message issued by the competent body authorizing an importer/buyer to transfer an amount of foreign exchange to an exporter/seller in payment for goods.
     */
    const _926 = "926";

    /**
     * Exchange control declaration (import).
     * Document/message completed by an importer/buyer as a means for the competent body to control that a trade transaction for which foreign exchange has been allocated has been executed and that money has been transferred in accordance with the conditions of payment and the exchange control regulations in force.
     */
    const _927 = "927";

    /**
     * Goods declaration for importation.
     * Document/message by which goods are declared for import Customs clearance [sister entry of 830].
     */
    const _929 = "929";

    /**
     * Goods declaration for home use.
     * Document/message by which goods are declared for import Customs clearance according to Annex B.1 (concerning clearance for home use) to the Kyoto convention (CCC).
     */
    const _930 = "930";

    /**
     * Customs immediate release declaration.
     * Document/message issued by an importer notifying Customs that goods have been removed from an importing means of transport to the importer's premises under a Customs- approved arrangement for immediate release, or requesting authorization to do so.
     */
    const _931 = "931";

    /**
     * Customs delivery note.
     * Document/message whereby a Customs authority releases goods under its control to be placed at the disposal of the party concerned. Synonym: Customs release note.
     */
    const _932 = "932";

    /**
     * Cargo declaration (arrival).
     * Generic term, sometimes referred to as Freight declaration, applied to the documents providing the particulars required by the Customs concerning the cargo (freight) carried by commercial means of transport (CCC).
     */
    const _933 = "933";

    /**
     * Value declaration.
     * Document/message in which a declarant (importer) states the invoice or other price (e.g. selling price, price of identical goods), and specifies costs for freight, insurance and packing, etc., terms of delivery and payment, any relationship with the trading partner, etc., for the purpose of determining the Customs value of goods imported.
     */
    const _934 = "934";

    /**
     * Customs invoice.
     * Document/message required by the Customs in an importing country in which an exporter states the invoice or other price (e.g. selling price, price of identical goods), and specifies costs for freight, insurance and packing, etc., terms of delivery and payment, for the purpose of determining the Customs value in the importing country of goods consigned to that country.
     */
    const _935 = "935";

    /**
     * Customs declaration (post parcels).
     * Document/message which, according to Article 106 of the "Agreement concerning Postal Parcels" under the UPU Convention, must accompany post parcels and in which the contents of such parcels are specified.
     */
    const _936 = "936";

    /**
     * Tax declaration (value added tax).
     * Document/message in which an importer states the pertinent information required by the competent body for assessment of value-added tax.
     */
    const _937 = "937";

    /**
     * Tax declaration (general).
     * Document/message containing a general tax declaration.
     */
    const _938 = "938";

    /**
     * Tax demand.
     * Document/message containing the demand of tax.
     */
    const _940 = "940";

    /**
     * Embargo permit.
     * Document/message giving the permission to export specified goods.
     */
    const _941 = "941";

    /**
     * Goods declaration for Customs transit.
     * Document/message by which the sender declares goods for Customs transit according to Annex E.1 (concerning Customs transit) to the Kyoto convention (CCC).
     */
    const _950 = "950";

    /**
     * TIF form.
     * International Customs transit document by which the sender declares goods for carriage by rail in accordance with the provisions of the 1952 International Convention to facilitate the crossing of frontiers for goods carried by rail (TIF Convention of UIC).
     */
    const _951 = "951";

    /**
     * TIR carnet.
     * International Customs document (International Transit by Road), issued by a guaranteeing association approved by the Customs authorities, under the cover of which goods are carried, in most cases under Customs seal, in road vehicles and/or containers in compliance with the requirements of the Customs TIR Convention of the International Transport of Goods under cover of TIR Carnets (UN/ECE).
     */
    const _952 = "952";

    /**
     * EC carnet.
     * EC customs transit document issued by EC customs authorities for transit and/or temporary user of goods within the EC.
     */
    const _953 = "953";

    /**
     * EUR 1 certificate of origin.
     * Customs certificate used in preferential goods interchanges between EC countries and EC external countries.
     */
    const _954 = "954";

    /**
     * ATA carnet.
     * International Customs document (Admission Temporaire / Temporary Admission) which, issued under the terms of the ATA Convention (1961), incorporates an internationally valid guarantee and may be used, in lieu of national Customs documents and as security for import duties and taxes, to cover the temporary admission of goods and, where appropriate, the transit of goods. If accepted for controlling the temporary export and reimport of goods, international guarantee does not apply (CCC).
     */
    const _955 = "955";

    /**
     * Single administrative document.
     * A set of documents, replacing the various (national) forms for Customs declaration within the EC, implemented on 01-01-1988.
     */
    const _960 = "960";

    /**
     * General response (Customs).
     * General response message to permit the transfer of data from Customs to the transmitter of the previous message.
     */
    const _961 = "961";

    /**
     * Document response (Customs).
     * Document response message to permit the transfer of data from Customs to the transmitter of the previous message.
     */
    const _962 = "962";

    /**
     * Error response (Customs).
     * Error response message to permit the transfer of data from Customs to the transmitter of the previous message.
     */
    const _963 = "963";

    /**
     * Package response (Customs).
     * Package response message to permit the transfer of data from Customs to the transmitter of the previous message.
     */
    const _964 = "964";

    /**
     * Tax calculation/confirmation response (Customs).
     * Tax calculation/confirmation response message to permit the transfer of data from Customs to the transmitter of the previous message.
     */
    const _965 = "965";

    /**
     * Quota prior allocation certificate.
     * Document/message issued by the competent body for prior allocation of a quota.
     */
    const _966 = "966";

    /**
     * Wagon report.
     * Document which contains consignment information concerning the wagons and their lading in a case of a multiple wagon consignment.
     */
    const _970 = "970";

    /**
     * Transit Conveyor Document.
     * Document for a course of transit used for a carrier who is neither the carrier at the beginning nor the arrival. The transit carrier can directly invoice the expenses for its part of the transport.
     */
    const _971 = "971";

    /**
     * Rail consignment note forwarder copy.
     * Document which is a copy of the rail consignment note printed especially for the need of the forwarder.
     */
    const _972 = "972";

    /**
     * Duty suspended goods.
     * Document giving details for the carriage of excisable goods on a duty-suspended basis.
     */
    const _974 = "974";

    /**
     * Proof of transit declaration.
     * A document providing proof that a transit declaration has been accepted.
     */
    const _975 = "975";

    /**
     * Container transfer note.
     * Document for the carriage of containers. Syn: transfer note.
     */
    const _976 = "976";

    /**
     * NATO transit document.
     * Customs transit document for the carriage of shipments of the NATO armed forces under Customs supervision.
     */
    const _977 = "977";

    /**
     * Transfrontier waste shipment authorization.
     * Document containing the authorization from the relevant authority for the international carriage of waste. Syn: Transfrontier waste shipment permit.
     */
    const _978 = "978";

    /**
     * Transfrontier waste shipment movement document.
     * Document certified by the carriers and the consignee to be used for the international carriage of waste.
     */
    const _979 = "979";

    /**
     * End use authorization.
     * Document issued by Customs granting the end-use Customs procedure.
     */
    const _990 = "990";

    /**
     * Government contract.
     * Document/message describing a contract with a government authority.
     */
    const _991 = "991";

    /**
     * Statistical document, import.
     * Document/message describing an import document that is used for statistical purposes.
     */
    const _995 = "995";

    /**
     * Application for documentary credit.
     * Message with application for opening of a documentary credit.
     */
    const _996 = "996";

    /**
     * Previous Customs document/message.
     * Indication of the previous Customs document/message concerning the same transaction.
     */
    const _998 = "998";

    static function name(string $code): string
    {
        switch ($code)
        {
            case self::_1:		return "Certificate of analysis";
            case self::_2:		return "Certificate of conformity";
            case self::_3:		return "Certificate of quality";
            case self::_4:		return "Test report";
            case self::_5:		return "Product performance report";
            case self::_6:		return "Product specification report";
            case self::_7:		return "Process data report";
            case self::_8:		return "First sample test report";
            case self::_9:		return "Price/sales catalogue";
            case self::_10:		return "Party information";
            case self::_11:		return "Federal label approval";
            case self::_12:		return "Mill certificate";
            case self::_13:		return "Post receipt";
            case self::_14:		return "Weight certificate";
            case self::_15:		return "Weight list";
            case self::_16:		return "Certificate";
            case self::_17:		return "Combined certificate of value and origin";
            case self::_18:		return "Movement certificate A.TR.1";
            case self::_19:		return "Certificate of quantity";
            case self::_20:		return "Quality data message";
            case self::_21:		return "Query";
            case self::_22:		return "Response to query";
            case self::_23:		return "Status information";
            case self::_24:		return "Restow";
            case self::_25:		return "Container discharge list";
            case self::_26:		return "Corporate superannuation contributions advice";
            case self::_27:		return "Industry superannuation contributions advice";
            case self::_28:		return "Corporate superannuation member maintenance message";
            case self::_29:		return "Industry superannuation member maintenance message";
            case self::_30:		return "Life insurance payroll deductions advice";
            case self::_31:		return "Underbond request";
            case self::_32:		return "Underbond approval";
            case self::_33:		return "Certificate of sealing of export meat lockers";
            case self::_34:		return "Cargo status";
            case self::_35:		return "Inventory report";
            case self::_36:		return "Identity card";
            case self::_37:		return "Response to a trade statistics message";
            case self::_38:		return "Vaccination certificate";
            case self::_39:		return "Passport";
            case self::_40:		return "Driving licence (national)";
            case self::_41:		return "Driving licence (international)";
            case self::_42:		return "Free pass";
            case self::_43:		return "Season ticket";
            case self::_44:		return "Transport status report";
            case self::_45:		return "Transport status request";
            case self::_46:		return "Banking status";
            case self::_47:		return "Extra-Community trade statistical declaration";
            case self::_48:		return "Written instructions in conformance with ADR article number";
            case self::_49:		return "Damage certification";
            case self::_50:		return "Validated priced tender";
            case self::_51:		return "Price/sales catalogue response";
            case self::_52:		return "Price negotiation result";
            case self::_53:		return "Safety and hazard data sheet";
            case self::_54:		return "Legal statement of an account";
            case self::_55:		return "Listing statement of an account";
            case self::_56:		return "Closing statement of an account";
            case self::_57:		return "Transport equipment on-hire report";
            case self::_58:		return "Transport equipment off-hire report";
            case self::_59:		return "Treatment - nil outturn";
            case self::_60:		return "Treatment - time-up underbond";
            case self::_61:		return "Treatment - underbond by sea";
            case self::_62:		return "Treatment - personal effect";
            case self::_63:		return "Treatment - timber";
            case self::_64:		return "Preliminary credit assessment";
            case self::_65:		return "Credit cover";
            case self::_66:		return "Current account";
            case self::_67:		return "Commercial dispute";
            case self::_68:		return "Chargeback";
            case self::_69:		return "Reassignment";
            case self::_70:		return "Collateral account";
            case self::_71:		return "Request for payment";
            case self::_72:		return "Unship permit";
            case self::_73:		return "Statistical definitions";
            case self::_74:		return "Statistical data";
            case self::_75:		return "Request for statistical data";
            case self::_76:		return "Call-off delivery";
            case self::_77:		return "Consignment status report";
            case self::_78:		return "Inventory movement advice";
            case self::_79:		return "Inventory status advice";
            case self::_80:		return "Debit note related to goods or services";
            case self::_81:		return "Credit note related to goods or services";
            case self::_82:		return "Metered services invoice";
            case self::_83:		return "Credit note related to financial adjustments";
            case self::_84:		return "Debit note related to financial adjustments";
            case self::_85:		return "Customs manifest";
            case self::_86:		return "Vessel unpack report";
            case self::_87:		return "General cargo summary manifest report";
            case self::_88:		return "Consignment unpack report";
            case self::_89:		return "Meat and meat by-products sanitary certificate";
            case self::_90:		return "Meat food products sanitary certificate";
            case self::_91:		return "Poultry sanitary certificate";
            case self::_92:		return "Horsemeat sanitary certificate";
            case self::_93:		return "Casing sanitary certificate";
            case self::_94:		return "Pharmaceutical sanitary certificate";
            case self::_95:		return "Inedible sanitary certificate";
            case self::_96:     return "Impending arrival";
            case self::_97:		return "Means of transport advice";
            case self::_98:		return "Arrival information";
            case self::_99:		return "Cargo release notification";
            case self::_100:	return "Excise certificate";
            case self::_101:	return "Registration document";
            case self::_102:	return "Tax notification";
            case self::_103:	return "Transport equipment direct interchange report";
            case self::_104:	return "Transport equipment impending arrival advice";
            case self::_105:	return "Purchase order";
            case self::_106:	return "Transport equipment damage report";
            case self::_107:	return "Transport equipment maintenance and repair work estimate";
            case self::_108:	return "Transport equipment empty release instruction";
            case self::_109:	return "Transport movement gate in report";
            case self::_110:	return "Manufacturing instructions";
            case self::_111:	return "Transport movement gate out report";
            case self::_112:	return "Transport equipment unpacking instruction";
            case self::_113:	return "Transport equipment unpacking report";
            case self::_114:	return "Transport equipment pick-up availability request";
            case self::_115:	return "Transport equipment pick-up availability confirmation";
            case self::_116:	return "Transport equipment pick-up report";
            case self::_117:	return "Transport equipment shift report";
            case self::_118:	return "Transport discharge instruction";
            case self::_119:	return "Transport discharge report";
            case self::_120:	return "Stores requisition";
            case self::_121:	return "Transport loading instruction";
            case self::_122:	return "Transport loading report";
            case self::_123:	return "Transport equipment maintenance and repair work";
            case self::_124:	return "Transport departure report";
            case self::_125:	return "Transport empty equipment advice";
            case self::_126:	return "Transport equipment acceptance order";
            case self::_127:	return "Transport equipment special service instruction";
            case self::_128:	return "Transport equipment stock report";
            case self::_129:	return "Transport cargo release order";
            case self::_130:	return "Invoicing data sheet";
            case self::_131:	return "Transport equipment packing instruction";
            case self::_132:	return "Customs clearance notice";
            case self::_133:	return "Customs documents expiration notice";
            case self::_134:	return "Transport equipment on-hire request";
            case self::_135:	return "Transport equipment on-hire order";
            case self::_136:	return "Transport equipment off-hire request";
            case self::_137:	return "Transport equipment survey order";
            case self::_138:	return "Transport equipment survey order response";
            case self::_139:	return "Transport equipment survey report";
            case self::_140:	return "Packing instructions";
            case self::_141:	return "Advising items to be booked to a financial account";
            case self::_142:	return "Transport equipment maintenance and repair work estimate";
            case self::_143:	return "Transport equipment maintenance and repair notice";
            case self::_144:	return "Empty container disposition order";
            case self::_145:	return "Cargo vessel discharge order";
            case self::_146:	return "Cargo vessel loading order";
            case self::_147:	return "Multidrop order";
            case self::_148:	return "Bailment contract";
            case self::_149:	return "Basic agreement";
            case self::_150:	return "Internal transport order";
            case self::_151:	return "Grant";
            case self::_152:	return "Indefinite delivery indefinite quantity contract";
            case self::_153:	return "Indefinite delivery definite quantity contract";
            case self::_154:	return "Requirements contract";
            case self::_155:	return "Task order";
            case self::_156:	return "Make or buy plan";
            case self::_157:	return "Subcontractor plan";
            case self::_158:	return "Cost data summary";
            case self::_159:	return "Certified cost and price data";
            case self::_160:	return "Wage determination";
            case self::_161:	return "Contract Funds Status Report (CFSR)";
            case self::_162:	return "Certified inspection and test results";
            case self::_163:	return "Material inspection and receiving report";
            case self::_164:	return "Purchasing specification";
            case self::_165:	return "Payment or performance bond";
            case self::_166:	return "Contract security classification specification";
            case self::_167:	return "Manufacturing specification";
            case self::_168:	return "Buy America certificate of compliance";
            case self::_169:	return "Container off-hire notice";
            case self::_170:	return "Cargo acceptance order";
            case self::_171:	return "Pick-up notice";
            case self::_172:	return "Authorisation to plan and suggest orders";
            case self::_173:	return "Authorisation to plan and ship orders";
            case self::_174:	return "Drawing";
            case self::_175:	return "Cost Performance Report (CPR) format 2";
            case self::_176:	return "Cost Schedule Status Report (CSSR)";
            case self::_177:	return "Cost Performance Report (CPR) format 1";
            case self::_178:	return "Cost Performance Report (CPR) format 3";
            case self::_179:	return "Cost Performance Report (CPR) format 4";
            case self::_180:	return "Cost Performance Report (CPR) format 5";
            case self::_181:	return "Progressive discharge report";
            case self::_182:	return "Balance confirmation";
            case self::_183:	return "Container stripping order";
            case self::_184:	return "Container stuffing order";
            case self::_185:	return "Conveyance declaration (arrival)";
            case self::_186:	return "Conveyance declaration (departure)";
            case self::_187:	return "Conveyance declaration (combined)";
            case self::_188:	return "Project recovery plan";
            case self::_189:	return "Project production plan";
            case self::_190:	return "Statistical and other administrative internal documents";
            case self::_191:	return "Project master schedule";
            case self::_192:	return "Priced alternate tender bill of quantity";
            case self::_193:	return "Estimated priced bill of quantity";
            case self::_194:	return "Draft bill of quantity";
            case self::_195:	return "Documentary credit collection instruction";
            case self::_196:	return "Request for an amendment of a documentary credit";
            case self::_197:	return "Documentary credit amendment information";
            case self::_198:	return "Advice of an amendment of a documentary credit";
            case self::_199:	return "Response to an amendment of a documentary credit";
            case self::_200:	return "Documentary credit issuance information";
            case self::_201:	return "Direct payment valuation request";
            case self::_202:	return "Direct payment valuation";
            case self::_203:	return "Provisional payment valuation";
            case self::_204:	return "Payment valuation";
            case self::_205:	return "Quantity valuation";
            case self::_206:	return "Quantity valuation request";
            case self::_207:	return "Contract bill of quantities - BOQ";
            case self::_208:	return "Unpriced bill of quantity";
            case self::_209:	return "Priced tender BOQ";
            case self::_210:	return "Enquiry";
            case self::_211:	return "Interim application for payment";
            case self::_212:	return "Agreement to pay";
            case self::_213:	return "Request for financial cancellation";
            case self::_214:	return "Pre-authorised direct debit(s)";
            case self::_215:	return "Letter of intent";
            case self::_216:	return "Approved unpriced bill of quantity";
            case self::_217:	return "Payment valuation for unscheduled items";
            case self::_218:	return "Final payment request based on completion of work";
            case self::_219:	return "Payment request for completed units";
            case self::_220:	return "Order";
            case self::_221:	return "Blanket order";
            case self::_222:	return "Spot order";
            case self::_223:	return "Lease order";
            case self::_224:	return "Rush order";
            case self::_225:	return "Repair order";
            case self::_226:	return "Call off order";
            case self::_227:	return "Consignment order";
            case self::_228:	return "Sample order";
            case self::_229:	return "Swap order";
            case self::_230:	return "Purchase order change request";
            case self::_231:	return "Purchase order response";
            case self::_232:	return "Hire order";
            case self::_233:	return "Spare parts order";
            case self::_234:	return "Campaign price/sales catalogue";
            case self::_235:	return "Container list";
            case self::_236:	return "Delivery forecast";
            case self::_237:	return "Cross docking services order";
            case self::_238:	return "Non-pre-authorised direct debit(s)";
            case self::_239:	return "Rejected direct debit(s)";
            case self::_240:	return "Delivery instructions";
            case self::_241:	return "Delivery schedule";
            case self::_242:	return "Delivery just-in-time";
            case self::_243:	return "Pre-authorised direct debit request(s)";
            case self::_244:	return "Non-pre-authorised direct debit request(s)";
            case self::_245:	return "Delivery release";
            case self::_246:	return "Settlement of a letter of credit";
            case self::_247:	return "Bank to bank funds transfer";
            case self::_248:	return "Customer payment order(s)";
            case self::_249:	return "Low value payment order(s)";
            case self::_250:	return "Crew list declaration";
            case self::_251:	return "Inquiry";
            case self::_252:	return "Response to previous banking status message";
            case self::_253:	return "Project master plan";
            case self::_254:	return "Project plan";
            case self::_255:	return "Project schedule";
            case self::_256:	return "Project planning available resources";
            case self::_257:	return "Project planning calendar";
            case self::_258:	return "Standing order";
            case self::_259:	return "Cargo movement event log";
            case self::_260:	return "Cargo analysis voyage report";
            case self::_261:	return "Self billed credit note";
            case self::_262:	return "Consolidated credit note - goods and services";
            case self::_263:	return "Inventory adjustment status report";
            case self::_264:	return "Transport equipment movement instruction";
            case self::_265:	return "Transport equipment movement report";
            case self::_266:	return "Transport equipment status change report";
            case self::_267:	return "Fumigation certificate";
            case self::_268:	return "Wine certificate";
            case self::_269:	return "Wool health certificate";
            case self::_270:	return "Delivery note";
            case self::_271:	return "Packing list";
            case self::_272:	return "New code request";
            case self::_273:	return "Code change request";
            case self::_274:	return "Simple data element request";
            case self::_275:	return "Simple data element change request";
            case self::_276:	return "Composite data element request";
            case self::_277:	return "Composite data element change request";
            case self::_278:	return "Segment request";
            case self::_279:	return "Segment change request";
            case self::_280:	return "New message request";
            case self::_281:	return "Message in development request";
            case self::_282:	return "Modification of existing message";
            case self::_283:	return "Tracking number assignment report";
            case self::_284:	return "User directory definition";
            case self::_285:	return "United Nations standard message request";
            case self::_286:	return "Service directory definition";
            case self::_287:	return "Status report";
            case self::_288:	return "Kanban schedule";
            case self::_289:	return "Product data message";
            case self::_290:	return "A claim for parts and/or labour charges";
            case self::_291:	return "Delivery schedule response";
            case self::_292:	return "Inspection request";
            case self::_293:	return "Inspection report";
            case self::_294:	return "Application acknowledgement and error report";
            case self::_295:	return "Price variation invoice";
            case self::_296:	return "Credit note for price variation";
            case self::_297:	return "Instruction to collect";
            case self::_298:	return "Dangerous goods list";
            case self::_299:	return "Registration renewal";
            case self::_300:	return "Registration change";
            case self::_301:	return "Response to registration";
            case self::_302:	return "Implementation guideline";
            case self::_303:	return "Request for transfer";
            case self::_304:	return "Cost performance report";
            case self::_305:	return "Application error and acknowledgement";
            case self::_306:	return "Cash pool financial statement";
            case self::_307:	return "Sequenced delivery schedule";
            case self::_308:	return "Delcredere credit note";
            case self::_309:	return "Healthcare discharge report, final";
            case self::_310:	return "Offer / quotation";
            case self::_311:	return "Request for quote";
            case self::_312:	return "Acknowledgement message";
            case self::_313:	return "Application error message";
            case self::_314:	return "Cargo movement voyage summary";
            case self::_315:	return "Contract";
            case self::_316:	return "Application for usage of berth or mooring facilities";
            case self::_317:	return "Application for designation of berthing places";
            case self::_318:	return "Application for shifting from the designated place in port";
            case self::_319:	return "Supplementary document for application for cargo operation";
            case self::_320:	return "Acknowledgement of order";
            case self::_321:	return "Supplementary document for application for transport of";
            case self::_322:	return "Optical Character Reading (OCR) payment";
            case self::_323:	return "Preliminary sales report";
            case self::_324:	return "Transport emergency card";
            case self::_325:	return "Proforma invoice";
            case self::_326:	return "Partial invoice";
            case self::_327:	return "Operating instructions";
            case self::_328:	return "Name/product plate";
            case self::_329:	return "Co-insurance ceding bordereau";
            case self::_330:	return "Request for delivery instructions";
            case self::_331:	return "Commercial invoice which includes a packing list";
            case self::_332:	return "Trade data";
            case self::_333:	return "Customs declaration for cargo examination";
            case self::_334:	return "Customs declaration for cargo examination, alternate";
            case self::_335:	return "Booking request";
            case self::_336:	return "Customs crew and conveyance";
            case self::_337:	return "Customs summary declaration with commercial detail,";
            case self::_338:	return "Items booked to a financial account report";
            case self::_339:	return "Report of transactions which need further information from";
            case self::_340:	return "Shipping instructions";
            case self::_341:	return "Shipper's letter of instructions (air)";
            case self::_342:	return "Report of transactions for information only";
            case self::_343:	return "Cartage order (local transport)";
            case self::_344:	return "EDI associated object administration message";
            case self::_345:	return "Ready for despatch advice";
            case self::_346:	return "Summary sales report";
            case self::_347:	return "Order status enquiry";
            case self::_348:	return "Order status report";
            case self::_349:	return "Declaration regarding the inward and outward movement of";
            case self::_350:	return "Despatch order";
            case self::_351:	return "Despatch advice";
            case self::_352:	return "Notification of usage of berth or mooring facilities";
            case self::_353:	return "Application for vessel's entering into port area in night-";
            case self::_354:	return "Notification of emergency shifting from the designated";
            case self::_355:	return "Customs summary declaration without commercial detail,";
            case self::_356:	return "Performance bond";
            case self::_357:	return "Payment bond";
            case self::_358:	return "Healthcare discharge report, preliminary";
            case self::_359:	return "Request for provision of a health service";
            case self::_360:	return "Request for price quote";
            case self::_361:	return "Price quote";
            case self::_362:	return "Delivery quote";
            case self::_363:	return "Price and delivery quote";
            case self::_364:	return "Contract price quote";
            case self::_365:	return "Contract price and delivery quote";
            case self::_366:	return "Price quote, specified end-customer";
            case self::_367:	return "Price and delivery quote, specified end-customer";
            case self::_368:	return "Price quote, ship and debit";
            case self::_369:	return "Price and delivery quote, ship and debit";
            case self::_370:	return "Advice of distribution of documents";
            case self::_371:	return "Plan for provision of health service";
            case self::_372:	return "Prescription";
            case self::_373:	return "Prescription request";
            case self::_374:	return "Prescription dispensing report";
            case self::_375:	return "Certificate of shipment";
            case self::_376:	return "Standing inquiry on product information";
            case self::_377:	return "Party credit information";
            case self::_378:	return "Party payment behaviour information";
            case self::_379:	return "Request for metering point information";
            case self::_380:	return "Invoice";
            case self::_381:	return "CreditNote";
            case self::_382:	return "Commission note";
            case self::_383:	return "Debit note";
            case self::_384:	return "Corrected invoice";
            case self::_385:	return "Consolidated invoice";
            case self::_386:	return "Prepayment invoice";
            case self::_387:	return "Hire invoice";
            case self::_388:	return "Tax invoice";
            case self::_389:	return "Self-billed invoice";
            case self::_390:	return "Delcredere invoice";
            case self::_391:	return "Metering point information response";
            case self::_392:	return "Notification of change of supplier";
            case self::_393:	return "Factored invoice";
            case self::_394:	return "Lease invoice";
            case self::_395:	return "Consignment invoice";
            case self::_396:	return "Factored credit note";
            case self::_397:	return "Commercial account summary response";
            case self::_398:	return "Cross docking despatch advice";
            case self::_399:	return "Transshipment despatch advice";
            case self::_400:	return "Exceptional order";
            case self::_401:	return "Pre-packed cross docking order";
            case self::_402:	return "Intermediate handling cross docking order";
            case self::_403:	return "Means of transportation availability information";
            case self::_404:	return "Means of transportation schedule information";
            case self::_405:	return "Transport equipment delivery notice";
            case self::_406:	return "Notification to supplier of contract termination";
            case self::_407:	return "Notification to supplier of metering point changes";
            case self::_408:	return "Notification of meter change";
            case self::_409:	return "Instructions for bank transfer";
            case self::_410:	return "Notification of metering point identification change";
            case self::_411:	return "Utilities time series message";
            case self::_412:	return "Application for banker's draft";
            case self::_413:	return "Infrastructure condition";
            case self::_414:	return "Acknowledgement of change of supplier";
            case self::_415:	return "Data Plot Sheet";
            case self::_416:	return "Soil analysis";
            case self::_417:	return "Farmyard manure analysis";
            case self::_418:	return "WCO Cargo Report Export, Rail or Road";
            case self::_419:	return "WCO Cargo Report Export, Air or Maritime";
            case self::_420:	return "Optical Character Reading (OCR) payment credit note";
            case self::_421:	return "WCO Cargo Report Import, Rail or Road";
            case self::_422:	return "WCO Cargo Report Import, Air or Maritime";
            case self::_423:	return "WCO one-step export declaration";
            case self::_1999:	return "Kyoto Convention.";
            case self::_424:	return "WCO first step of two-step export declaration";
            case self::_425:	return "Collection payment advice";
            case self::_426:	return "Documentary credit payment advice";
            case self::_427:	return "Documentary credit acceptance advice";
            case self::_428:	return "Documentary credit negotiation advice";
            case self::_429:	return "Application for banker's guarantee";
            case self::_430:	return "Banker's guarantee";
            case self::_431:	return "Documentary credit letter of indemnity";
            case self::_432:	return "Notification to grid operator of contract termination";
            case self::_433:	return "Notification to grid operator of metering point changes";
            case self::_434:	return "Notification of balance responsible entity change";
            case self::_435:	return "Preadvice of a credit";
            case self::_436:	return "Transport equipment profile report";
            case self::_437:	return "Request for price and delivery quote, specified end-user";
            case self::_438:	return "Request for price quote, ship and debit";
            case self::_439:	return "Request for price and delivery quote, ship and debit";
            case self::_440:	return "Delivery point list.";
            case self::_441:	return "Transport routing information";
            case self::_442:	return "Request for delivery quote";
            case self::_443:	return "Request for price and delivery quote";
            case self::_444:	return "Request for contract price quote";
            case self::_445:	return "Request for contract price and delivery quote";
            case self::_446:	return "Request for price quote, specified end-customer";
            case self::_447:	return "Collection order";
            case self::_448:	return "Documents presentation form";
            case self::_449:	return "Identification match";
            case self::_450:	return "Payment order";
            case self::_451:	return "Extended payment order";
            case self::_452:	return "Multiple payment order";
            case self::_453:	return "Notice that circumstances prevent payment of delivered";
            case self::_454:	return "Credit advice";
            case self::_455:	return "Extended credit advice";
            case self::_456:	return "Debit advice";
            case self::_457:	return "Reversal of debit";
            case self::_458:	return "Reversal of credit";
            case self::_459:	return "Travel ticket";
            case self::_460:	return "Documentary credit application";
            case self::_461:	return "Payment card";
            case self::_462:	return "Ready for transshipment despatch advice";
            case self::_463:	return "Pre-packed cross docking despatch advice";
            case self::_464:	return "Intermediate handling cross docking despatch advice";
            case self::_465:	return "Documentary credit";
            case self::_466:	return "Documentary credit notification";
            case self::_467:	return "Documentary credit transfer advice";
            case self::_468:	return "Documentary credit amendment notification";
            case self::_469:	return "Documentary credit amendment";
            case self::_470:	return "Waste disposal report";
            case self::_481:	return "Remittance advice";
            case self::_482:	return "Port authority waste disposal report";
            case self::_483:	return "Visa";
            case self::_484:	return "Multiple direct debit request";
            case self::_485:	return "Banker's draft";
            case self::_486:	return "Multiple direct debit";
            case self::_487:	return "Certificate of disembarkation permission";
            case self::_488:	return "Deratting exemption certificate";
            case self::_489:	return "Reefer connection order";
            case self::_490:	return "Bill of exchange";
            case self::_491:	return "Promissory note";
            case self::_493:	return "Statement of account message";
            case self::_494:	return "Direct delivery (transport)";
            case self::_495:	return "WCO second step of two-step export declaration";
            case self::_496:	return "WCO one-step import declaration";
            case self::_497:	return "WCO first step of two-step import declaration";
            case self::_498:	return "WCO second step of two-step import declaration";
            case self::_499:	return "Previous transport document";
            case self::_520:	return "Insurance certificate";
            case self::_521:	return "Special requirements permit related to the transport of";
            case self::_522:	return "Dangerous Goods Notification for Tanker vessel";
            case self::_523:	return "Dangerous Goods Notification for non-tanker vessel";
            case self::_524:	return "WCO Conveyance Arrival Report";
            case self::_525:	return "WCO Conveyance Departure Report";
            case self::_526:	return "Accounting voucher";
            case self::_527:	return "Self billed debit note";
            case self::_528:	return "Military Identification Card";
            case self::_529:	return "Re-Entry Permit";
            case self::_530:	return "Insurance policy";
            case self::_531:	return "Refugee Permit";
            case self::_532:	return "Forwarder's credit note";
            case self::_533:	return "Original accounting voucher";
            case self::_534:	return "Copy accounting voucher";
            case self::_535:	return "Pro-forma accounting voucher";
            case self::_536:	return "International Ship Security Certificate";
            case self::_537:	return "Interim International Ship Security Certificate";
            case self::_538:	return "Good Manufacturing Practice (GMP) Certificate";
            case self::_539:	return "Framework Agreement";
            case self::_550:	return "Insurance declaration sheet (bordereau)";
            case self::_551:	return "Transport capacity offer";
            case self::_552:	return "Ship Security Plan";
            case self::_553:	return "Forwarder's invoice discrepancy report";
            case self::_554:	return "Storage capacity offer";
            case self::_575:	return "Insurer's invoice";
            case self::_576:	return "Storage capacity request";
            case self::_577:	return "Transport capacity request";
            case self::_578:	return "EU Customs declaration for External Community Transit (T1)";
            case self::_579:	return "EU Customs declaration for internal Community Transit (T2)";
            case self::_580:	return "Cover note";
            case self::_581:	return "EU Customs declaration for non-fiscal area internal";
            case self::_582:	return "EU Customs declaration for internal transit to San Marino";
            case self::_583:	return "EU Customs declaration for mixed consignments (T)";
            case self::_584:	return "EU Document for establishing the Community status of goods";
            case self::_585:	return "EU Document for establishing the Community status of goods";
            case self::_586:	return "Document for establishing the Customs Status of goods for";
            case self::_587:	return "Customs declaration for TIR Carnet goods";
            case self::_588:	return "Transport Means Security Report";
            case self::_589:	return "Halal Slaughtering Certificate";
            case self::_610:	return "Forwarding instructions";
            case self::_621:	return "Forwarder's advice to import agent";
            case self::_622:	return "Forwarder's advice to exporter";
            case self::_623:	return "Forwarder's invoice";
            case self::_624:	return "Forwarder's certificate of receipt";
            case self::_625:	return "Heat Treatment Certificate";
            case self::_626:	return "Convention on International Trade in Endangered Species of";
            case self::_627:	return "Free Sale Certificate in the Country of Origin";
            case self::_628:	return "Transit license";
            case self::_629:	return "Veterinary quarantine certificate";
            case self::_630:	return "Shipping note";
            case self::_631:	return "Forwarder's warehouse receipt";
            case self::_632:	return "Goods receipt";
            case self::_633:	return "Port charges documents";
            case self::_634:	return "Certified list of ingredients";
            case self::_635:	return "Warehouse warrant";
            case self::_636:	return "Health certificate";
            case self::_637:	return "Food grade certificate";
            case self::_638:	return "Certificate of suitability for transport of grains and";
            case self::_639:	return "Certificate of refrigerated transport equipment inspection";
            case self::_640:	return "Delivery order";
            case self::_641:	return "Thermographic reading report";
            case self::_642:	return "Certificate of food item transport readiness";
            case self::_643:	return "Food packaging contact certificate";
            case self::_644:	return "Packaging material composition report";
            case self::_645:	return "Export price certificate";
            case self::_646:	return "Public price certificate";
            case self::_647:	return "Drug shelf life study report";
            case self::_648:	return "Certificate of compliance with standards of the World";
            case self::_649:	return "Production facility license";
            case self::_650:	return "Handling order";
            case self::_651:	return "Manufacturing license";
            case self::_652:	return "Low risk country formal letter";
            case self::_653:	return "Previous correspondence";
            case self::_654:	return "Declaration for radioactive material";
            case self::_655:	return "Gate pass";
            case self::_656:	return "Resale information";
            case self::_657:	return "Phytosanitary Re-export Certificate";
            case self::_658:	return "Bayplan/stowage plan, full";
            case self::_659:	return "Bayplan/stowage plan, partial";
            case self::_700:	return "Waybill";
            case self::_701:	return "Universal (multipurpose) transport document";
            case self::_702:	return "Goods receipt, carriage";
            case self::_703:	return "House waybill";
            case self::_704:	return "Master bill of lading";
            case self::_705:	return "Bill of lading";
            case self::_706:	return "Bill of lading original";
            case self::_707:	return "Bill of lading copy";
            case self::_708:	return "Empty container bill";
            case self::_709:	return "Tanker bill of lading";
            case self::_710:	return "Sea waybill";
            case self::_711:	return "Inland waterway bill of lading";
            case self::_712:	return "Non-negotiable maritime transport document (generic)";
            case self::_713:	return "Mate's receipt";
            case self::_714:	return "House bill of lading";
            case self::_715:	return "Letter of indemnity for non-surrender of bill of lading";
            case self::_716:	return "Forwarder's bill of lading";
            case self::_717:	return "Residence permit";
            case self::_718:	return "Seaman's book";
            case self::_719:	return "General message";
            case self::_720:	return "Rail consignment note (generic term)";
            case self::_721:	return "Product data response";
            case self::_722:	return "Road list-SMGS";
            case self::_723:	return "Escort official recognition";
            case self::_724:	return "Recharging document";
            case self::_725:	return "Manufacturer raised order";
            case self::_726:	return "Manufacturer raised consignment order";
            case self::_727:	return "Price/sales catalogue not containing commercial information";
            case self::_728:	return "Price/sales catalogue containing commercial information";
            case self::_729:	return "Returns advice";
            case self::_730:	return "Road consignment note";
            case self::_731:	return "Commercial account summary";
            case self::_732:	return "Announcement for returns";
            case self::_733:	return "Instruction for returns";
            case self::_734:	return "Sales forecast report";
            case self::_735:	return "Sales data report";
            case self::_736:	return "Standing inquiry on complete product information";
            case self::_737:	return "Proof of delivery";
            case self::_738:	return "Cargo/goods handling and movement message";
            case self::_739:	return "Metered services consumption report supporting an invoice";
            case self::_740:	return "Air waybill";
            case self::_741:	return "Master air waybill";
            case self::_742:	return "Metered services consumption report";
            case self::_743:	return "Substitute air waybill";
            case self::_744:	return "Crew's effects declaration";
            case self::_745:	return "Passenger list";
            case self::_746:	return "Delivery notice (rail transport)";
            case self::_747:	return "Payroll deductions advice";
            case self::_748:	return "Consignment despatch advice";
            case self::_749:	return "Transport equipment gross mass verification message";
            case self::_750:	return "Despatch note (post parcels)";
            case self::_751:	return "Invoice information for accounting purposes";
            case self::_760:	return "Multimodal/combined transport document (generic)";
            case self::_761:	return "Through bill of lading";
            case self::_763:	return "Forwarder's certificate of transport";
            case self::_764:	return "Combined transport document (generic)";
            case self::_765:	return "Multimodal transport document (generic)";
            case self::_766:	return "Combined transport bill of lading/multimodal bill of lading";
            case self::_770:	return "Booking confirmation";
            case self::_775:	return "Calling forward notice";
            case self::_780:	return "Freight invoice";
            case self::_781:	return "Arrival notice (goods)";
            case self::_782:	return "Notice of circumstances preventing delivery (goods)";
            case self::_783:	return "Notice of circumstances preventing transport (goods)";
            case self::_784:	return "Delivery notice (goods)";
            case self::_785:	return "Cargo manifest";
            case self::_786:	return "Freight manifest";
            case self::_787:	return "Bordereau";
            case self::_788:	return "Container manifest (unit packing list)";
            case self::_789:	return "Charges note";
            case self::_790:	return "Advice of collection";
            case self::_791:	return "Safety of ship certificate";
            case self::_792:	return "Safety of radio certificate";
            case self::_793:	return "Safety of equipment certificate";
            case self::_794:	return "Civil liability for oil certificate";
            case self::_795:	return "Loadline document";
            case self::_796:	return "Derat document";
            case self::_797:	return "Maritime declaration of health";
            case self::_798:	return "Certificate of registry";
            case self::_799:	return "Ship's stores declaration";
            case self::_810:	return "Export licence, application for";
            case self::_811:	return "Export licence";
            case self::_812:	return "Exchange control declaration, export";
            case self::_820:	return "Despatch note model T";
            case self::_821:	return "Despatch note model T1";
            case self::_822:	return "Despatch note model T2";
            case self::_823:	return "Control document T5";
            case self::_824:	return "Re-sending consignment note";
            case self::_825:	return "Despatch note model T2L";
            case self::_830:	return "Goods declaration for exportation";
            case self::_833:	return "Cargo declaration (departure)";
            case self::_840:	return "Application for goods control certificate";
            case self::_841:	return "Goods control certificate";
            case self::_850:	return "Application for phytosanitary certificate";
            case self::_851:	return "Phytosanitary certificate";
            case self::_852:	return "Sanitary certificate";
            case self::_853:	return "Veterinary certificate";
            case self::_855:	return "Application for inspection certificate";
            case self::_856:	return "Inspection certificate";
            case self::_860:	return "Certificate of origin, application for";
            case self::_861:	return "Certificate of origin";
            case self::_862:	return "Declaration of origin";
            case self::_863:	return "Regional appellation certificate";
            case self::_864:	return "Preference certificate of origin";
            case self::_865:	return "Certificate of origin form GSP";
            case self::_870:	return "Consular invoice";
            case self::_890:	return "Dangerous goods declaration";
            case self::_895:	return "Statistical document, export";
            case self::_896:	return "INTRASTAT declaration";
            case self::_901:	return "Delivery verification certificate";
            case self::_910:	return "Import licence, application for";
            case self::_911:	return "Import licence";
            case self::_913:	return "Customs declaration without commercial detail";
            case self::_914:	return "Customs declaration with commercial and item detail";
            case self::_915:	return "Customs declaration without item detail";
            case self::_916:	return "Related document";
            case self::_917:	return "Receipt (Customs)";
            case self::_925:	return "Application for exchange allocation";
            case self::_926:	return "Foreign exchange permit";
            case self::_927:	return "Exchange control declaration (import)";
            case self::_929:	return "Goods declaration for importation";
            case self::_930:	return "Goods declaration for home use";
            case self::_931:	return "Customs immediate release declaration";
            case self::_932:	return "Customs delivery note";
            case self::_933:	return "Cargo declaration (arrival)";
            case self::_934:	return "Value declaration";
            case self::_935:	return "Customs invoice";
            case self::_936:	return "Customs declaration (post parcels)";
            case self::_937:	return "Tax declaration (value added tax)";
            case self::_938:	return "Tax declaration (general)";
            case self::_940:	return "Tax demand";
            case self::_941:	return "Embargo permit";
            case self::_950:	return "Goods declaration for Customs transit";
            case self::_951:	return "TIF form";
            case self::_952:	return "TIR carnet";
            case self::_953:	return "EC carnet";
            case self::_954:	return "EUR 1 certificate of origin";
            case self::_955:	return "ATA carnet";
            case self::_960:	return "Single administrative document";
            case self::_961:	return "General response (Customs)";
            case self::_962:	return "Document response (Customs)";
            case self::_963:	return "Error response (Customs)";
            case self::_964:	return "Package response (Customs)";
            case self::_965:	return "Tax calculation/confirmation response (Customs)";
            case self::_966:	return "Quota prior allocation certificate";
            case self::_970:	return "Wagon report";
            case self::_971:	return "Transit Conveyor Document";
            case self::_972:	return "Rail consignment note forwarder copy";
            case self::_974:	return "Duty suspended goods";
            case self::_975:	return "Proof of transit declaration";
            case self::_976:	return "Container transfer note";
            case self::_977:	return "NATO transit document";
            case self::_978:	return "Transfrontier waste shipment authorization";
            case self::_979:	return "Transfrontier waste shipment movement document";
            case self::_990:	return "End use authorization";
            case self::_991:	return "Government contract";
            case self::_995:	return "Statistical document, import";
            case self::_996:	return "Application for documentary credit";
            case self::_998:	return "Previous Customs document/message";
            default:            return $code;
        }
    }
}