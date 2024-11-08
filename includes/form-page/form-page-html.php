<?php
if (!defined('ABSPATH')) {
    exit;
}
function render_use_case_form() {
 ob_start();
 ?>
    <form id="simple-contact-form__form">
        <h2>Submit your use case</h2>
        <div class="form-group">
            <div id="project-name">
                <div class="form-label">Project Name</div>
                <input name="project_name" type="text" placeholder="Project Name">
            </div>
            <div id="project-owner">
                <div class="form-label">Project Owner</div>
                <input name="name" type="text" placeholder="Name">
            </div>
            <div id="email">
                <div class="form-label">Email</div>
                <input name="creator_email" type="email" placeholder="Email">
            </div>
            <div id="w-minor">
                <div class="form-label">Windesheim Minor</div>
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
                <div class="form-label">Value Chain</div>
                <label><input type="checkbox" name="value_chain[]" value="Inbound logistics"> Inbound logistics</label>
                <label><input type="checkbox" name="value_chain[]" value="Operations"> Operations</label>
                <label><input type="checkbox" name="value_chain[]" value="Outbound logistics"> Outbound logistics</label>
                <label><input type="checkbox" name="value_chain[]" value="Marketing and sales"> Marketing and sales</label>
                <label><input type="checkbox" name="value_chain[]" value="Service"> Service</label>
                <label><input type="checkbox" name="value_chain[]" value="Firm infrastructure"> Firm infrastructure</label>
                <label><input type="checkbox" name="value_chain[]" value="Human resource management"> Human resource management</label>
                <label><input type="checkbox" name="value_chain[]" value="Technology"> Technology</label>
                <label><input type="checkbox" name="value_chain[]" value="Procurement"> Procurement</label>
            </div>
            <div id="project-phase">
                <div class="form-label">Project Phase</div>
                <label><input type="radio" name="project_phase" value="Asses"> Asses</label>
                <label><input type="radio" name="project_phase" value="Trial"> Trial</label>
                <label><input type="radio" name="project_phase" value="Adopt"> Adopt</label>
            </div>
            <div id="technological-innovations">
                <div class="form-label">Technological Innovations</div>
                <textarea name="techn_innovations" placeholder="Type your message"></textarea>
            </div>
            <div id="project-image">
                <div class="form-label">Project Image</div>
                <input name="project_image" type="file" accept="image/*">
            </div>
            <div id="tech-providers">
                <div class="form-label">Tech Providers</div>
                <textarea name="tech_providers" placeholder="Type your message"></textarea>
            </div>
            <div id="innovation-sectors">
                <div class="form-label">Innovation Sectors</div>
                <label><input type="radio" name="innovation_sectors" value="Culture & Media"> Culture & Media</label>
                <label><input type="radio" name="innovation_sectors" value="Data Sharing"> Data Sharing</label>
                <label><input type="radio" name="innovation_sectors" value="Department of Defense"> Department of Defense</label>
                <label><input type="radio" name="innovation_sectors" value="ELSA Labs"> ELSA Labs</label>
                <label><input type="radio" name="innovation_sectors" value="Energy & Sustainability"> Energy & Sustainability</label>
                <label><input type="radio" name="innovation_sectors" value="Financial Services"> Financial Services</label>
                <label><input type="radio" name="innovation_sectors" value="Health & Care"> Health & Care</label>
                <label><input type="radio" name="innovation_sectors" value="Port & Maritime"> Port & Maritime</label>
                <label><input type="radio" name="innovation_sectors" value="Agriculture & Nutrition"> Agriculture & Nutrition</label>
                <label><input type="radio" name="innovation_sectors" value="Logistics & Mobility"> Logistics & Mobility</label>
                <label><input type="radio" name="innovation_sectors" value="Human-centered AI"> Human-centered AI</label>
                <label><input type="radio" name="innovation_sectors" value="Mobility, Transport & Logistics"> Mobility, Transport & Logistics</label>
                <label><input type="radio" name="innovation_sectors" value="Education"> Education</label>
                <label><input type="radio" name="innovation_sectors" value="Public Services"> Public Services</label>
                <label><input type="radio" name="innovation_sectors" value="Research & Innovation"> Research & Innovation</label>
                <label><input type="radio" name="innovation_sectors" value="Startups & Scaleups"> Startups & Scaleups</label>
                <label><input type="radio" name="innovation_sectors" value="Technical Industry"> Technical Industry</label>
                <label><input type="radio" name="innovation_sectors" value="Security, Peace & Justice"> Security, Peace & Justice</label>
            </div>
            <div id="themes">
                <div class="form-label">Themes</div>
                <label><input type="checkbox" name="themes[]" value="Transaction to interaction"> Transaction to interaction</label>
                <label><input type="checkbox" name="themes[]" value="Future of Work"> Future of Work</label>
                <label><input type="checkbox" name="themes[]" value="Cloud Everywhere"> Cloud Everywhere</label>
                <label><input type="checkbox" name="themes[]" value="Future of Programming"> Future of Programming</label>
                <label><input type="checkbox" name="themes[]" value="Next UI"> Next UI</label>
                <label><input type="checkbox" name="themes[]" value="Building Trust"> Building Trust</label>
                <label><input type="checkbox" name="themes[]" value="Green Tech"> Green Tech</label>
                <label><input type="checkbox" name="themes[]" value="Quantum computing"> Quantum computing</label>
            </div>
            <div id="sdgs">
                <div class="form-label">SDGs</div>
                <label><input type="checkbox" name="sdgs[]" value="1. No poverty"> 1. No poverty</label>
                <label><input type="checkbox" name="sdgs[]" value="2. No hunger"> 2. No hunger</label>
                <label><input type="checkbox" name="sdgs[]" value="3. Good health and well-being"> 3. Good health and well-being</label>
                <label><input type="checkbox" name="sdgs[]" value="4. Quality education"> 4. Quality education</label>
                <label><input type="checkbox" name="sdgs[]" value="5. Gender equality"> 5. Gender equality</label>
                <label><input type="checkbox" name="sdgs[]" value="6. Clean water and sanitation"> 6. Clean water and sanitation</label>
                <label><input type="checkbox" name="sdgs[]" value="7. Affordable and sustainable energy"> 7. Affordable and sustainable energy</label>
                <label><input type="checkbox" name="sdgs[]" value="8. Decent work and economic growth"> 8. Decent work and economic growth</label>
                <label><input type="checkbox" name="sdgs[]" value="9. Industry, innovation and infrastructure"> 9. Industry, innovation and infrastructure</label>
                <label><input type="checkbox" name="sdgs[]" value="10. Reduce inequality"> 10. Reduce inequality</label>
                <label><input type="checkbox" name="sdgs[]" value="11. Sustainable cities and communities"> 11. Sustainable cities and communities</label>
                <label><input type="checkbox" name="sdgs[]" value="12. Responsible consumption and production"> 12. Responsible consumption and production</label>
                <label><input type="checkbox" name="sdgs[]" value="13. Climate action"> 13. Climate action</label>
                <label><input type="checkbox" name="sdgs[]" value="14. Life in the water"> 14. Life in the water</label>
                <label><input type="checkbox" name="sdgs[]" value="15. Life on land"> 15. Life on land</label>
                <label><input type="checkbox" name="sdgs[]" value="16. Peace, justice and strong public services"> 16. Peace, justice and strong public services</label>
                <label><input type="checkbox" name="sdgs[]" value="17. Partnership to achieve goals"> 17. Partnership to achieve goals</label>
            </div>
            <div id="positive-impact-sdgs">
                <div class="form-label">Positive Impact SDGs</div>
                <textarea name="positive_impact_sdgs" placeholder="Type your message"></textarea>
            </div>
            <div id="negative-impact-sdgs">
                <div class="form-label">Negative Impact SDGs</div>
                <textarea name="negative_impact_sdgs" placeholder="Type your message"></textarea>
            </div>
            <div id="project-background">
                <div class="form-label">Project Background</div>
                <textarea name="project_background" placeholder="Type your message"></textarea>
            </div>
            <div id="problem">
                <div class="form-label">Problem to Solve</div>
                <textarea name="problem" placeholder="Type your message"></textarea>
            </div>
            <div id="smart-goal">
                <div class="form-label">Smart Goal</div>
                <textarea name="smart_goal" placeholder="Type your message"></textarea>
            </div>
            <div id="project-link">
                <div class="form-label">Project Link</div>
                <input name="project_link" type="text" placeholder="Project Link">
            </div>
            <div id="video-link">
                <div class="form-label">Video Link</div>
                <input name="video_link" type="text" placeholder="Video Link">
            </div>
        </div>
        <button type="submit">Send</button>
    </form>
 <?php
 return ob_get_clean();
}
?>