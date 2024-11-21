<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('UseCaseFormChecking')) {
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
            'Asses',
            'Trial',
            'Adopt'
        ];

        private $valid_innovation_sectors = [
            'Culture & Media',
            'Data Sharing',
            'Department of Defense',
            'ELSA Labs',
            'Energy & Sustainability',
            'Financial Services',
            'Health & Care',
            'Port & Maritime',
            'Agriculture & Nutrition',
            'Logistics & Mobility',
            'Human-centered AI',
            'Mobility, Transport & Logistics',
            'Education',
            'Public Services',
            'Research & Innovation',
            'Startups & Scaleups',
            'Technical Industry',
            'Security, Peace & Justice',
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
            '1. No poverty',
            '2. No hunger',
            '3. Good health and well-being',
            '4. Quality education',
            '5. Gender equality',
            '6. Clean water and sanitation',
            '7. Affordable and sustainable energy',
            '8. Decent work and economic growth',
            '9. Industry, innovation and infrastructure',
            '10. Reduce inequality',
            '11. Sustainable cities and communities',
            '12. Responsible consumption and production',
            '13. Climate action',
            '14. Life in the water',
            '15. Life on land',
            '16. Peace, justice and strong public services',
            '17. Partnership to achieve goals'
        ];

        public function __construct()
        {
        }

        public function checkForm($data)
        {
            $errors = [];
            if (empty($data)) {
                $errors['empty'] = 'No data was submitted';
                return $errors;
            }

            if (isset($data['name'])) {
                $this->checkName($data['name'], $errors);
            } else {
                $errors['project-owner'] = 'Name is required';
            }

            if (isset($data['creator_email'])) {
                $this->checkEmail($data['creator_email'], $errors);
            } else {
                $errors['email'] = 'Email is required';
            }

            if (isset($data['w_minor'])) {
                $this->checkMinor($data['w_minor'], $errors);
            }

            if (isset($data['value_chain'])) {
                $this->checkValueChain($data['value_chain'], $errors);
            } else {
                $errors['value-chain'] = 'Value chain is required';
            }

            if (isset($data['project_phase'])) {
                $this->checkProjectPhase($data['project_phase'], $errors);
            } else {
                $errors['project-phase'] = 'Project phase is required';
            }

            if (isset($data['innovation_sectors'])) {
                $this->checkInnovationSector($data['innovation_sectors'], $errors);
            } else {
                $errors['innovation-sectors'] = 'Innovation sector is required';
            }

            if (isset($data['themes'])) {
                $this->checkTheme($data['themes'], $errors);
            } else {
                $errors['themes'] = 'Theme is required';
            }
            if (isset($data['sdgs'])) {
                $this->checkSDGs($data['sdgs'], $errors);
            } else {
                $errors['sdgs'] = 'SDGs is required';
            }

            if (isset($data['project_name'])) {
                $this->checkProjectName($data['project_name'], $errors);
            } else {
                $errors['project-name'] = 'Project name is required';
            }

            if (isset($data['techn_innovations'])) {
                $this->checkTechInnovations($data['techn_innovations'], $errors);
            } else {
                $errors['technological-innovations'] = 'Tech innovations is required';
            }

            if (isset($data['tech_providers'])) {
                $this->checkTechProviders($data['tech_providers'], $errors);
            } else {
                $errors['tech-providers'] = 'Tech providers is required';
            }

            if (isset($data['project_background'])) {
                $this->checkProjectBackground($data['project_background'], $errors);
            } else {
                $errors['project-background'] = 'Project background is required';
            }

            if (isset($data['problem'])) {
                $this->checkProblem($data['problem'], $errors);
            } else {
                $errors['problem'] = 'Problem is required';
            }

            if (isset($data['smart_goal'])) {
                $this->checkSmartGoal($data['smart_goal'], $errors);
            } else {
                $errors['smart-goal'] = 'Smart goal is required';
            }

            if (isset($data['project_link'])) {
                $this->checkProjectLink($data['project_link'], $errors);
            } else {
                $errors['project-link'] = 'Project link is required';
            }

            if (isset($data['video_link'])) {
                $this->checkVideoLink($data['video_link'], $errors);
            } else {
                $errors['video-link'] = 'Video link is required';
            }
            //return errors
            return $errors;
        }


        private function checkProjectName($project_name, &$errors)
        {
            if (empty($project_name)) {
                $errors['project-name'] = 'Project name is required';
            }
        }

        private function checkTechInnovations($tech_innovations, &$errors)
        {
            if (empty($tech_innovations)) {
                $errors['technological-innovations'] = 'Tech innovations is required';
            }
        }

        private function checkTechProviders($tech_providers, &$errors)
        {
            if (empty($tech_providers)) {
                $errors['tech-providers'] = 'Tech providers is required';
            }
        }

        private function checkProjectBackground($project_background, &$errors)
        {
            if (empty($project_background)) {
                $errors['project-background'] = 'Project background is required';
            }
        }

        private function checkProblem($problem, &$errors)
        {
            if (empty($problem)) {
                $errors['problem'] = 'Problem is required';
            }
        }

        private function checkSmartGoal($smart_goal, &$errors)
        {
            if (empty($smart_goal)) {
                $errors['smart-goal'] = 'Smart goal is required';
            }
        }

        private function checkProjectLink($project_link, &$errors)
        {
            if (empty($project_link)) {
                $errors['project-link'] = 'Project link is required';
            } else if (!filter_var($project_link, FILTER_VALIDATE_URL)) {
                $errors['project-link'] = 'Project link is not valid';
            }
        }

        private function checkVideoLink($video_link, &$errors)
        {
            if (!empty($video_link)) {

                if (!filter_var($video_link, FILTER_VALIDATE_URL)) {
                    $errors['video-link'] = 'Video link is not valid';
                }
            }
        }

        private function checkName($name, &$errors)
        {
            if (empty($name)) {
                $errors['project-owner'] = 'Name is required';
            } //No numbers allowed
            else if (preg_match('/[0-9]/', $name)) {
                $errors['project-owner'] = 'Name cannot contain numbers';
            }
        }

        private function checkEmail($email, &$errors)
        {
            if (empty($email)) {
                $errors['email'] = 'Email is required';
            } else if (!is_email($email)) {
                $errors['email'] = 'Email is not valid';
            }
        }

        private function checkMinor($minor, &$errors)
        {
            if (!empty($minor)) {

                if (!is_string($minor)) {
                    $errors['w-minor'] = 'Minor is not valid';
                } else if (!in_array($minor, $this->valid_minors)) {
                    $errors['w-minor'] = 'Minor is not valid';
                }
            }
        }

        private function checkValueChain($value_chain, &$errors)
        {
            if (empty($value_chain)) {
                $errors['value-chain'] = 'Value chain is required';
                return;
            }
            // No duplicate values allowed
            if (count($value_chain) !== count(array_unique($value_chain))) {
                $errors['value-chain'] = 'Value chain cannot contain duplicate values';
                return;
            }

            // Check if all values are valid
            foreach ($value_chain as $value) {
                if (!in_array($value, $this->valid_value_chain)) {
                    $errors['value-chain'] = 'Value chain is not valid';
                    break;
                }
            }
        }

        private function checkProjectPhase($project_phase, &$errors)
        {
            if (empty($project_phase)) {
                $errors['project-phase'] = 'Project phase is required';
            } else if (!is_string($project_phase)) {
                $errors['project-phase'] = 'Project phase is not valid';
            } else if (!in_array($project_phase, $this->valid_project_phase)) {
                $errors['project-phase'] = 'Project phase is not valid';
            }
        }

        private function checkInnovationSector($innovation_sector, &$errors)
        {
            if (empty($innovation_sector)) {
                $errors['innovation-sectors'] = 'Innovation sector is required';
                return;
            } else if (!is_string($innovation_sector)) {
                $errors['innovation-sectors'] = 'Innovation sector is not valid';
            } else if (!in_array($innovation_sector, $this->valid_innovation_sectors)) {
                $errors['innovation-sectors'] = 'Innovation sector is not valid';
            }
        }

        private function checkTheme($theme, &$errors)
        {
            if (empty($theme)) {
                $errors['themes'] = 'Theme is required';
                return;
            }
            // No duplicate values allowed
            if (count($theme) !== count(array_unique($theme))) {
                $errors['themes'] = 'Theme cannot contain duplicate values';
                return;
            }

            // Check if all values are valid
            foreach ($theme as $value) {
                if (!in_array($value, $this->valid_themes)) {
                    $errors['themes'] = 'Theme is not valid';
                    break;
                }
            }

        }

        private function checkSDGs($sdgs, &$errors)
        {
            if (empty($sdgs)) {
                $errors['sdgs'] = 'SDGs is required';
                return;
            }
            // No duplicate values allowed
            if (count($sdgs) !== count(array_unique($sdgs))) {
                $errors['sdgs'] = 'SDGs cannot contain duplicate values';
                return;
            }

            // Check if all values are valid
            foreach ($sdgs as $value) {
                if (!in_array($value, $this->valid_sdgs)) {
                    $errors['sdgs'] = 'SDGs is not valid';
                    break;
                }
            }
        }
    }
}