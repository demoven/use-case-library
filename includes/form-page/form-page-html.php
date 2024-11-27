<?php
if (!defined('ABSPATH')) {
    exit;
}
function render_use_case_form()
{
    ob_start();
    ?>
    <div id="success-message">
        Use case sent successfully
    </div>
    <div id="general-error"></div>
    <form id="simple-contact-form__form">
        <h2>Submit your use case</h2>
        <div class="form-group">
            <div id="project-name">
                <div class="form-label">Wat is de naam van uw project? <span class="required">*</span></div>
                <input name="project_name" type="text" placeholder="Naam...">
                <span class="error-message"></span>
            </div>
            <div id="project-owner">
                <div class="form-label">Product Owner<span class="required">*</span></div>
                <input name="name" type="text" placeholder="Product Owner...">
                <span class="error-message"></span>
            </div>
            <div id="email">
                <div class="form-label">Email<span class="required">*</span></div>
                <input name="creator_email" type="email" placeholder="Email...">
                <span class="error-message"></span>
            </div>
            <div id="w-minor">
                <div class="form-label">Windesheim Minor</div>
                <span class="error-message"></span>
                <label><input type="radio" name="w_minor" value="Concept & Creation"> Concept & Creation</label>
                <label><input type="radio" name="w_minor" value="Data driven Innovation"> Data driven Innovation</label>
                <label><input type="radio" name="w_minor" value="Entrepreneurships"> Entrepreneurships</label>
                <label><input type="radio" name="w_minor" value="Future Technology"> Future Technology</label>
                <label><input type="radio" name="w_minor" value="Game Studio"> Game Studio</label>
                <label><input type="radio" name="w_minor" value="Mobile Solutions"> Mobile Solutions</label>
                <label><input type="radio" name="w_minor" value="Security Engineering"> Security Engineering</label>
                <label><input type="radio" name="w_minor" value="Web & Analytics"> Web & Analytics</label>
            </div>
            <div id="value-chain">
                <div class="form-label">Value Chain<span class="required">*</span></div>
                <span class="error-message"></span>
                <label><input type="checkbox" name="value_chain[]" value="Inbound logistics"> Inbound logistics</label>
                <label><input type="checkbox" name="value_chain[]" value="Operations"> Operations</label>
                <label><input type="checkbox" name="value_chain[]" value="Outbound logistics"> Outbound
                    logistics</label>
                <label><input type="checkbox" name="value_chain[]" value="Marketing and sales"> Marketing and
                    sales</label>
                <label><input type="checkbox" name="value_chain[]" value="Service"> Service</label>
                <label><input type="checkbox" name="value_chain[]" value="Firm infrastructure"> Firm
                    infrastructure</label>
                <label><input type="checkbox" name="value_chain[]" value="Human resource management"> Human resource
                    management</label>
                <label><input type="checkbox" name="value_chain[]" value="Technology"> Technology</label>
                <label><input type="checkbox" name="value_chain[]" value="Procurement"> Procurement</label>
            </div>
            <div id="project-phase">
                <div class="form-label">
                    <p>In welke fase bevindt het project zich? <span class="required">*</span></p>
                    <p class="project-phase-paragraph"><span class="project-phase-underlined">Assess:</span> Technologieën die veelbelovend zijn en een duidelijke potentiële toegevoegde waarde voor ons hebben.</p>
                    <p class="project-phase-paragraph"><span class="project-phase-underlined">Trial:</span> Technologieën waarvan we hebben gezien dat ze met succes werken om een echt probleem op te lossen.</p>
                    <p class="project-phase-paragraph"><span class="project-phase-underlined">Adopt:</span> Technologieën in productie.</p></div>
                <span class="error-message"></span>
                <label><input type="radio" name="project_phase" value="Assess"> Assess</label>
                <label><input type="radio" name="project_phase" value="Trial"> Trial</label>
                <label><input type="radio" name="project_phase" value="Adopt"> Adopt</label>
            </div>
            <div id="technological-innovations">
                <div class="form-label">Welke technologische innovaties zijn er binnen je project? (bijv. VR, AI,
                    Robotica...)<span class="required">*</span></div>
                <textarea name="techn_innovations" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="project-image">
                <div class="form-label">Project afbeelding</div>
                <input name="project_image" type="file" accept="image/png, image/jpeg">
                <small>PNG, JPEG</small>
            </div>
            <div id="tech-providers">
                <div class="form-label">Welke Technology Providers zijn betrokken bij je project?<span class="required">*</span>
                </div>
                <textarea name="tech_providers" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="innovation-sectors">
                <div class="form-label">Innovatie sector definieert jouw project?<span class="required">*</span></div>
                <span class="error-message"></span>
                <label><input type="radio" name="innovation_sectors" value="Cultuur en Media"> Cultuur en Media</label>
                <label><input type="radio" name="innovation_sectors" value="Data Delen"> Data Delen</label>
                <label><input type="radio" name="innovation_sectors" value="Defensie"> Defensie</label>
                <label><input type="radio" name="innovation_sectors" value="ELSA Labs"> ELSA Labs</label>
                <label><input type="radio" name="innovation_sectors" value="Energie en Duurzaamheid"> Energie en
                    Duurzaamheid</label>
                <label><input type="radio" name="innovation_sectors" value="Financiële Dienstverlening "> Financiële
                    Dienstverlening </label>
                <label><input type="radio" name="innovation_sectors" value="Gezondheid en Zorg"> Gezondheid en
                    Zorg</label>
                <label><input type="radio" name="innovation_sectors" value="Haven en Maritiem"> Haven en
                    Maritiem</label>
                <label><input type="radio" name="innovation_sectors" value="Landbouw en Voeding"> Landbouw en
                    Voeding</label>
                <label><input type="radio" name="innovation_sectors" value="Logistiek & Mobiliteit"> Logistiek &
                    Mobiliteit</label>
                <label><input type="radio" name="innovation_sectors" value="Mensgerichte AI"> Mensgerichte AI</label>
                <label><input type="radio" name="innovation_sectors" value="Mobiliteit, Transport en Logistiek">
                    Mobiliteit, Transport en Logistiek</label>
                <label><input type="radio" name="innovation_sectors" value="Onderwijs"> Onderwijs</label>
                <label><input type="radio" name="innovation_sectors" value="Publieke Diensten"> Publieke
                    Diensten</label>
                <label><input type="radio" name="innovation_sectors" value="Research en Innovatie"> Research en
                    Innovatie</label>
                <label><input type="radio" name="innovation_sectors" value="Startups en Scale-ups"> Startups en
                    Scale-ups</label>
                <label><input type="radio" name="innovation_sectors" value="Technische Industrie"> Technische Industrie</label>
                <label><input type="radio" name="innovation_sectors" value="Veiligheid, Vrede en Recht"> Veiligheid,
                    Vrede en Recht</label>
            </div>
            <div id="themes">
                <div class="form-label">Welke van deze thema's zijn van toepassing op jouw project?<span
                            class="required">*</span></div>
                <span class="error-message"></span>
                <label><input type="checkbox" name="themes[]" value="Transaction to interaction"> Transaction to
                    interaction</label>
                <label><input type="checkbox" name="themes[]" value="Future of Work"> Future of Work</label>
                <label><input type="checkbox" name="themes[]" value="Cloud Everywhere"> Cloud Everywhere</label>
                <label><input type="checkbox" name="themes[]" value="Future of Programming"> Future of
                    Programming</label>
                <label><input type="checkbox" name="themes[]" value="Next UI"> Next UI</label>
                <label><input type="checkbox" name="themes[]" value="Building Trust"> Building Trust</label>
                <label><input type="checkbox" name="themes[]" value="Green Tech"> Green Tech</label>
                <label><input type="checkbox" name="themes[]" value="Quantum computing"> Quantum computing</label>
            </div>
            <div id="sdgs">
                <div class="form-label">Welke SDG's zijn van toepassing op jouw project?<span class="required">*</span>
                </div>
                <span class="error-message"></span>
                <label><input type="checkbox" name="sdgs[]" value="1. Geen armoede"> 1. Geen armoede</label>
                <label><input type="checkbox" name="sdgs[]" value="2. Geen honger"> 2. Geen honger</label>
                <label><input type="checkbox" name="sdgs[]" value="3. Goede gezondheid en welzijn"> 3. Goede gezondheid
                    en welzijn</label>
                <label><input type="checkbox" name="sdgs[]" value="4. Kwaliteitsonderwijs"> 4.
                    Kwaliteitsonderwijs</label>
                <label><input type="checkbox" name="sdgs[]" value="5. Gendergelijkheid"> 5. Gendergelijkheid</label>
                <label><input type="checkbox" name="sdgs[]" value="6. Schoon water en sanitair"> 6. Schoon water en
                    sanitair</label>
                <label><input type="checkbox" name="sdgs[]" value="7. Betaalbare en duurzame energie"> 7. Betaalbare en
                    duurzame energie</label>
                <label><input type="checkbox" name="sdgs[]" value="8. Eerlijk werk en economische groei"> 8. Eerlijk
                    werk en economische groei</label>
                <label><input type="checkbox" name="sdgs[]" value="9. Industrie, innovatie en infrastructuur"> 9.
                    Industrie, innovatie en infrastructuur</label>
                <label><input type="checkbox" name="sdgs[]" value="10. Ongelijkheid verminderen"> 10. Ongelijkheid
                    verminderen</label>
                <label><input type="checkbox" name="sdgs[]" value="11. Duurzame steden en gemeenschappen"> 11. Duurzame
                    steden en gemeenschappen</label>
                <label><input type="checkbox" name="sdgs[]" value="12. Verantwoorde consumptie en productie"> 12.
                    Verantwoorde consumptie en productie</label>
                <label><input type="checkbox" name="sdgs[]" value="13. Klimaatactie"> 13. Klimaatactie</label>
                <label><input type="checkbox" name="sdgs[]" value="14. Leven in het water"> 14. Leven in het
                    water</label>
                <label><input type="checkbox" name="sdgs[]" value="15. Leven op het land"> 15. Leven op het land</label>
                <label><input type="checkbox" name="sdgs[]" value="16. Vrede, justitie en sterke publieke diensten"> 16.
                    Vrede, justitie en sterke publieke diensten</label>
                <label><input type="checkbox" name="sdgs[]" value="17. Partnerschap om doelstellingen te bereiken"> 17.
                    Partnerschap om doelstellingen te bereiken</label>
            </div>
            <div id="positive-impact-sdgs">
                <div class="form-label">Beschrijf de positieve impact van de SDG's</div>
                <textarea name="positive_impact_sdgs" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="negative-impact-sdgs">
                <div class="form-label">Beschrijf de negatieve impact van de SDG's</div>
                <textarea name="negative_impact_sdgs" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="project-background">
                <div class="form-label">Beschrijf hieronder de achtergrond van je project<span class="required">*</span>
                </div>
                <textarea name="project_background" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="problem">
                <div class="form-label">Welk probleem lost het project op?<span class="required">*</span></div>
                <textarea name="problem" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="smart-goal">
                <div class="form-label">Wat is het (SMART) doel van het project?<span class="required">*</span></div>
                <textarea name="smart_goal" placeholder="Typ je bericht..."></textarea>
                <span class="error-message"></span>
            </div>
            <div id="project-link">
                <div class="form-label">Link naar je case studie<span class="required">*</span></div>
                <input name="project_link" type="text" placeholder="Projectlink...">
                <span class="error-message"></span>
            </div>
            <div id="video-link">
                <div class="form-label">Heb je een videolink voor je project? Voeg die dan hier toe</div>
                <input name="video_link" type="text" placeholder="Videolink...">
                <span class="error-message"></span>
            </div>
        </div>
        <button type="submit">Verzend</button>
    </form>
    <?php
    return ob_get_clean();
}

?>