<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseFormChecking')) {

    /**
     * Class UseCaseFormChecking for checking form data.
     */
    class UseCaseFormChecking
    {
        private $valid_minors = [
            'Concept & Creation',
            'Data driven Innovation',
            'Entrepreneurships',
            'Future Technology',
            'Game Studio',
            'Mobile Solutions',
            'Security Engineering',
            'Web & Analytics'
        ];

        private $valid_value_chain = [
            'Inbound logistics',
            'Operations',
            'Outbound logistics',
            'Marketing and sales',
            'Service',
            'Firm infrastructure',
            'Human resource management',
            'Technology',
            'Procurement'
        ];

        private $valid_project_phase = [
            'Assess',
            'Trial',
            'Adopt'
        ];

        private $valid_innovation_sectors = [
            'Cultuur en Media',
            'Data Delen',
            'Defensie',
            'ELSA Labs',
            'Energie en Duurzaamheid',
            'Financiële Dienstverlening ',
            'Gezondheid en Zorg',
            'Haven en Maritiem',
            'Landbouw en Voeding',
            'Logistiek & Mobiliteit',
            'Mensgerichte AI',
            'Mobiliteit, Transport en Logistiek',
            'Onderwijs',
            'Publieke Diensten',
            'Research en Innovatie',
            'Startups en Scale-ups',
            'Technische Industrie',
            'Veiligheid, Vrede en Recht',
        ];

        private $valid_themes = [
            'Transaction to interaction',
            'Future of Work',
            'Cloud Everywhere',
            'Future of Programming',
            'Next UI',
            'Building Trust',
            'Green Tech',
            'Quantum computing'
        ];

        private $valid_sdgs = [
            '1. Geen armoede',
            '2. Geen honger',
            '3. Goede gezondheid en welzijn',
            '4. Kwaliteitsonderwijs',
            '5. Gendergelijkheid',
            '6. Schoon water en sanitair',
            '7. Betaalbare en duurzame energie',
            '8. Eerlijk werk en economische groei',
            '9. Industrie, innovatie en infrastructuur',
            '10. Ongelijkheid verminderen',
            '11. Duurzame steden en gemeenschappen',
            '12. Verantwoorde consumptie en productie',
            '13. Klimaatactie',
            '14. Leven in het water',
            '15. Leven op het land',
            '16. Vrede, justitie en sterke publieke diensten',
            '17. Partnerschap om doelstellingen te bereiken'
        ];

        public function __construct()
        {
        }


        /**
         * Check all form data.
         * 
         * @param array $data
         * @return array $errors
         */
        public function checkForm($data)
        {
            $errors = [];
            if (empty($data)) {
                $errors['empty'] = 'Er zijn geen gegevens ingediend';
                return $errors;
            }

            if (isset($data['name'])) {
                $this->checkName($data['name'], $errors);
            } else {
                $errors['project-owner'] = 'Naam is vereist';
            }

            if (isset($data['creator_email'])) {
                $this->checkEmail($data['creator_email'], $errors);
            } else {
                $errors['email'] = 'Email is vereist';
            }

            if (isset($data['w_minor'])) {
                $this->checkMinor($data['w_minor'], $errors);
            }

            if (isset($data['value_chain'])) {
                $this->checkValueChain($data['value_chain'], $errors);
            } else {
                $errors['value-chain'] = 'Value chain is vereist';
            }

            if (isset($data['project_phase'])) {
                $this->checkProjectPhase($data['project_phase'], $errors);
            } else {
                $errors['project-phase'] = 'Projectfase is vereist';
            }

            if (isset($data['innovation_sectors'])) {
                $this->checkInnovationSector($data['innovation_sectors'], $errors);
            } else {
                $errors['innovation-sectors'] = 'Innovatiesector is vereist';
            }

            if (isset($data['themes'])) {
                $this->checkTheme($data['themes'], $errors);
            } else {
                $errors['themes'] = 'Thema is vereist';
            }
            if (isset($data['sdgs'])) {
                $this->checkSDGs($data['sdgs'], $errors);
            } else {
                $errors['sdgs'] = 'SDGs is vereist';
            }

            if (isset($data['project_name'])) {
                $this->checkProjectName($data['project_name'], $errors);
            } else {
                $errors['project-name'] = 'Projectnaam is vereist';
            }

            if (isset($data['techn_innovations'])) {
                $this->checkTechInnovations($data['techn_innovations'], $errors);
            } else {
                $errors['technological-innovations'] = 'Technische innovaties zijn vereist';
            }

            if (isset($data['tech_providers'])) {
                $this->checkTechProviders($data['tech_providers'], $errors);
            } else {
                $errors['tech-providers'] = 'Techaanbieders zijn vereist';
            }

            if (isset($data['project_background'])) {
                $this->checkProjectBackground($data['project_background'], $errors);
            } else {
                $errors['project-background'] = 'Projectachtergrond is vereist';
            }

            if (isset($data['problem'])) {
                $this->checkProblem($data['problem'], $errors);
            } else {
                $errors['problem'] = 'Probleem is vereist';
            }

            if (isset($data['smart_goal'])) {
                $this->checkSmartGoal($data['smart_goal'], $errors);
            } else {
                $errors['smart-goal'] = 'Doel is vereist';
            }

            if (isset($data['project_link'])) {
                $this->checkProjectLink($data['project_link'], $errors);
            } else {
                $errors['project-link'] = 'Projectlink is vereist';
            }

            if (isset($data['video_link'])) {
                $this->checkVideoLink($data['video_link'], $errors);
            } else {
                $errors['video-link'] = 'Videolink is vereist';
            }

            if (isset($data['country'])) {
                $this->checkCountry($data['country'], $errors);
            } else {
                $errors['country'] = 'Land is vereist';
            }
            if (isset($data['zipcode'])) {
                $this->zipcode($data['zipcode'], $errors);
            }
            //return errors
            return $errors;
        }

        private function checkProjectName($project_name, &$errors)
        {
            if (empty($project_name)) {
                $errors['project-name'] = 'Projectnaam is vereist';
            }
        }

        private function checkTechInnovations($tech_innovations, &$errors)
        {
            if (empty($tech_innovations)) {
                $errors['technological-innovations'] = 'Technische innovaties zijn vereist';
            }
        }

        private function checkTechProviders($tech_providers, &$errors)
        {
            if (empty($tech_providers)) {
                $errors['tech-providers'] = 'Tech Providers zijn vereist';
            }
        }

        private function checkProjectBackground($project_background, &$errors)
        {
            if (empty($project_background)) {
                $errors['project-background'] = 'Project achtergrond is vereist';
            }
        }

        private function checkProblem($problem, &$errors)
        {
            if (empty($problem)) {
                $errors['problem'] = 'Probleem is vereist';
            }
        }

        private function checkSmartGoal($smart_goal, &$errors)
        {
            if (empty($smart_goal)) {
                $errors['smart-goal'] = 'Doel is vereist';
            }
        }

        private function checkProjectLink($project_link, &$errors)
        {
            if (empty($project_link)) {
                $errors['project-link'] = 'Projectlink is vereist';
            } else if (!filter_var($project_link, FILTER_VALIDATE_URL)) {
                $errors['project-link'] = 'Projectlink is ongeldig';
            }
        }

        private function checkVideoLink($video_link, &$errors)
        {
            if (!empty($video_link)) {

                if (!filter_var($video_link, FILTER_VALIDATE_URL)) {
                    $errors['video-link'] = 'Videolink is ongeldig';
                }
            }
        }

        private function checkName($name, &$errors)
        {
            if (empty($name)) {
                $errors['project-owner'] = 'Naam is vereist';
            } //No numbers allowed
            else if (preg_match('/[0-9]/', $name)) {
                $errors['project-owner'] = 'Naam kan geen getallen bevatten';
            }
        }

        private function checkEmail($email, &$errors)
        {
            if (empty($email)) {
                $errors['email'] = 'E-mail is vereist';
            } else if (!is_email($email)) {
                $errors['email'] = 'E-mail is ongeldig';
            }
        }

        private function checkMinor($minor, &$errors)
        {
            if (!empty($minor)) {

                if (!is_string($minor)) {
                    $errors['w-minor'] = 'Minor is ongeldig';
                } else if (!in_array($minor, $this->valid_minors)) {
                    $errors['w-minor'] = 'Minor is ongeldig';
                }
            }
        }

        private function checkValueChain($value_chain, &$errors)
        {
            if (empty($value_chain)) {
                $errors['value-chain'] = 'Value chain is vereist';
                return;
            }
            // No duplicate values allowed
            if (count($value_chain) !== count(array_unique($value_chain))) {
                $errors['value-chain'] = 'Value chain kan geen dubbele waarden bevatten';
                return;
            }

            // Check if all values are valid
            foreach ($value_chain as $value) {
                if (!in_array($value, $this->valid_value_chain)) {
                    $errors['value-chain'] = 'Value chain is ongeldig';
                    break;
                }
            }
        }

        private function checkProjectPhase($project_phase, &$errors)
        {
            if (empty($project_phase)) {
                $errors['project-phase'] = 'Projectfase is vereist';
            } else if (!is_string($project_phase)) {
                $errors['project-phase'] = 'Projectfase is ongeldig';
            } else if (!in_array($project_phase, $this->valid_project_phase)) {
                $errors['project-phase'] = 'Projectfase is ongeldig';
            }
        }

        private function checkInnovationSector($innovation_sector, &$errors)
        {
            if (empty($innovation_sector)) {
                $errors['innovation-sectors'] = 'Innovatie sector is vereist';
                return;
            } else if (!is_string($innovation_sector)) {
                $errors['innovation-sectors'] = 'Innovatie sector is ongeldig';
            } else if (!in_array($innovation_sector, $this->valid_innovation_sectors)) {
                $errors['innovation-sectors'] = 'Innovatie sector is ongeldig';
            }
        }

        private function checkTheme($theme, &$errors)
        {
            if (empty($theme)) {
                $errors['themes'] = 'Thema is vereist';
                return;
            }
            // No duplicate values allowed
            if (count($theme) !== count(array_unique($theme))) {
                $errors['themes'] = 'Thema kan geen dubbele waarden bevatten';
                return;
            }

            // Check if all values are valid
            foreach ($theme as $value) {
                if (!in_array($value, $this->valid_themes)) {
                    $errors['themes'] = 'Thema is ongeldig';
                    break;
                }
            }

        }

        private function checkSDGs($sdgs, &$errors)
        {
            if (empty($sdgs)) {
                $errors['sdgs'] = 'SDGs is vereist';
                return;
            }
            // No duplicate values allowed
            if (count($sdgs) !== count(array_unique($sdgs))) {
                $errors['sdgs'] = 'SDGs kan geen dubbele waarden bevatten';
                return;
            }

            // Check if all values are valid
            foreach ($sdgs as $value) {
                if (!in_array($value, $this->valid_sdgs)) {
                    $errors['sdgs'] = 'SDGs is ongeldig';
                    break;
                }
            }
        }

        private function checkCountry($country, &$errors)
        {
            if (empty($country)) {
                $errors['country'] = 'Land is vereist';
            }

            if (!is_string($country)) {
                $errors['country'] = 'Land is ongeldig';
            }

            if (strlen($country) > 40) {
                $errors['country'] = 'Land is te lang';
            }
        }

        private function zipcode($zipcode, &$errors)
        {
            if (!empty($zipcode)) {

                if (strlen($zipcode) > 6) {
                    $errors['zipcode'] = 'Postcode is te lang';
                }
            }
        }
    }
}