<?php
include_once('include/init_functions.php');
if(is_user_logged_in()){
  header('Location: index.php');
}

?>

<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta property="og:url" content="http://www.connectyou.com"/>
	<meta property="og:type" content="non_profit"/>
	<meta property="og:title" content="ConnectYou"/>
	<meta property="og:description" content="This is the description">

	<meta name="twitter:site" content="@connectyou">
	<meta name="twitter:title" content="ConnectYou">
	<meta name="twitter:description" content="This is the description">

	<meta itemprop="name" content="ConnectYou">
	<meta itemprop="description" content="This is the description.">


  <link rel="stylesheet" href="dist/css/main.css">
  <link rel="icon" href="dist/img/logos/logo48.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500" rel="stylesheet">
  <link href="dist/fontawesome/releases/v5.11.2/css/all.css" rel="stylesheet">


  <!-- FONTS
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Arvo" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Nunito+Sans|Roboto" rel="stylesheet">
	<link rel="stylesheet" id="redux-google-fonts-stm_option-css"
		href="https://fonts.googleapis.com/css?family=Montserrat%3A200%2C500%2C600%2C400%2C700%7COpen+Sans%3A300%2C400%2C600%2C700%2C800%2C300italic%2C400italic%2C600italic%2C700italic%2C800italic&#038;subset=latin&#038;ver=1536658178"
		 type="text/css" media="all" />
	<link rel="stylesheet" id="redux-google-fonts-stm_option-css"
		href="https://fonts.googleapis.com/css?family=Montserrat%3A200%2C500%2C600%2C400%2C700%7COpen+Sans%3A300%2C400%2C600%2C700%2C800%2C300italic%2C400italic%2C600italic%2C700italic%2C800italic&#038;subset=latin&#038;ver=1536658178"
		 type="text/css" media="all" />
  -->

  <title>Sign Up | ConnectYou</title>

</head>

<body>

  <div class="login_and_signup-background">
    <div class="inner_wrapper">
      <div class="register_auth_login">
        <div class="logo">
          <a href="#"><img src="dist/img/logos/logo152.png"></a>
        </div>
        <div class="login_register_box">
          <h1 class="title">Sign Up to ConnectYou</h1>
          <form autocomplete="off" class="cy-form" action="return false">
            <div class="form_group">
              <input type="text" name="user_username" id="register-username" class="input_with_borderErrors" placeholder="Username">
              <span class="form_group_icon">
                <i class="far fa-user"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="text" name="user_email" id="register-email" class="input_with_borderErrors" placeholder="Email">
              <span class="form_group_icon">
                <i class="fal fa-envelope"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="password" name="password" id="register-password" class="input_with_borderErrors" placeholder="Password">
              <span class="form_group_icon">
                <i class="fal fa-key"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="password" name="password_confirm" id="register-repassword" class="input_with_borderErrors" placeholder="Confirm Password">
              <span class="form_group_icon">
                <i class="fal fa-key"></i>
              </span>
            </div>
            <div class="form_group">
              <input type="checkbox" name="terms_and_cond" id="terms-conditions" class="hidden_checkbox">
              <label for="terms-conditions" class="checkbox_label">I agree to the <a data-target="terms_condition_modal">Terms & Conditions</a></label>
            </div>
          </form>


          <div class="login_button_container">
            <button class="form_btn" id="register">Sign up</button>
          </div>
          <div class="auth_external_message">
            <span>Already have an account? <a href="login.php">Log in </a> here!</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="terms_condition_modal">
    <div class="modal_container">
      <div class="modal_content">
        <div class="modal_header">
          <h4>Terms & Conditions</h4>
          <button type="button" aria-label="Close" class="close_modal">
            <span class="icon_bar"></span>
            <span class="icon_bar"></span>
          </button>
        </div>
        <div class="modal_body terms_cond_container">
          <p>
          Welcome to our website and thank you for your interest to become a part of the social movement of ConnectYou! ConnectYou (“us” or “we”) and our 
          services are developed available through the mobile-friendly website (the “Website” or the “Site”). Unless it is not specified, all references 
          to the Service or Services involve the set of services accessible through the ConnectYou Website, as well as any other software or social media 
          that ConnectYou offers that allows to get the Services. The term “you” indicates the user of the Service. The further Terms of Service are a form 
          of legal binding contract between you and ConnectYou as with the user of the Services. ConnectYou is owned and operated by ConnectYou Company, 
          a British Limited Liability Company.
          <br><br>
          Please read the following Terms of Service ("Terms") carefully before accessing or using the Service. Each time you access or use the Service, you, 
          or if you are acting on behalf of a third party or your employer, such third party or employer, agree to be bound by these Terms whether or not you 
          register with us. If you do not agree to be bound by all of these Terms, you may not access or use the Service. ConnectYou may change this 
          Agreement at any time by posting an updated Terms of Service on this site. If any amendment to these Terms is unacceptable to you, you shall 
          cease using this Site. If you continue using the Site, you will be conclusively deemed to have accepted the changes.
          </p>
          <p>
          PLEASE READ THE BINDING ARBITRATION CLAUSE AND CLASS ACTION WAIVER PROVISIONS IN THE DISPUTE RESOLUTION SECTION OF THESE TERMS. IT AFFECTS HOW DISPUTES 
          ARE RESOLVED.
          </p>
          <h5 class="sub_title">Description of Service</h5>
          <ul class="list_of_points">
            <li> ConnectYou is a mobile-friendly website that provides the platform for connecting users on the common interests through our Services. The Service 
              includes (i) ConnectYou systems, procedures, processes and technologies, and (ii) any hardware, software, applications, data, reports, and other content made 
              available by or on behalf of ConnectYou.
            </li>
            <li>
              The Service does not include any software application or service that is provided by you or a third party in any legal or illegal form, which you use in connection 
              with the Service.
            </li>
            <li>
            	Any modifications and new features added to the Service are also subject to this Agreement.
            </li>
            <li>
            ConnectYou reserves the right to modify or terminate the Service or any feature or functionality thereof at any time without notice to you. All rights, title and interest in 
            and to the Service and its components (including all intellectual property rights) will remain with and belong exclusively to ConnectYou. 
            </li>
          </ul>
          <h5 class="sub_title">Eligibility for Our Service</h5>
          <ul class="list_of_points">
            <li>By using our Service, you represent that you are at least 18 years of age. Persons who are at least 14 years of age but under the age of 18 may only use our Service with legal 
              parental or guardian consent.  Therefore, you agree that you are at least 18 years of age or older or possess legal parental or guardian consent and are fully able and competent to 
              enter into the terms, conditions, representations and warranties set forth in the Terms.
            </li>
          </ul>

          <h5 class="sub_title">Your Access and Use of our Services</h5>
          <ul class="list_of_points">
            <li>
            Your right to access and use our Service is personal to you and is not transferable in any form by you to any other person or entity except yourself. Access to our Service may not be possible 
            in all locations in the world. You are entitled to access and use our Service only for lawful purposes and pursuant to the terms and conditions of this Agreement and our Privacy Policy. Any action 
            by you that, in our sole discretion: (i) violates the terms and conditions of this Agreement and/or the Privacy Policy; (ii) restricts, inhibits or prevents any access, use or enjoyment of our Service; 
            or (iii) through the use of our Service, defames, abuses, harasses, offends or threatens others, shall not be permitted and may result in your loss of the right to access and use our Service.
            </li>
            <li>
            The rights granted to you in these Terms are subject to the following restrictions: (i) you shall not license, sell, rent, lease, transfer, assign, distribute, host, or otherwise commercially exploit the 
            Service; (ii) you shall not modify, make derivative works of, disassemble, reverse compile or reverse engineer any part of the Service; (iii) you shall not access the Service in order to build a similar or 
            competitive Service; and (iv) except as expressly stated herein, no part of the Service may be copied, reproduced, distributed, republished, downloaded, displayed, posted or transmitted in any form or by any 
            means. Any future release, update, or other addition to functionality of the Service shall be subject to these Terms.
            </li>
            <li>
            Furthermore, you agree that you will not use any robot, spider, scraper, deep link or other similar automated data gathering or extraction tools, programs, algorithms or methodologies to access, acquire, copy or 
            monitor our Services or any portion of our Service or for any other purpose, without our prior written permission.  Additionally, you agree that you will not: (i) take any action that enforces, or may enforce in our 
            sole discretion an unreasonable or disproportionately large load on our infrastructure; (ii) copy, reproduce, modify, create derivative works from, distribute or publicly display any content (except for your personal information) 
            from our Service without our prior written permission and the appropriate third party, as applicable; (iii) interfere or attempt to interfere with the proper working of our Service or any activities conducted on our Service; (iv) 
            bypass any robot exclusion headers or other measures we may use to prevent or restrict access to our Service, or (v) interfere or disrupt the Service or servers or networks connected to the Service, including by transmitting any worms, 
            viruses, spyware, malware or any other code of a destructive or disruptive nature.
            </li>
            <li>
            Except as expressly permitted in this Agreement, you shall not collect or harvest any personally identifiable information, including account names, from our Service.
            </li>
            <li>
            Our Service may now, or in the future, have "publicly accessible areas" that allow users to post User Content that will be accessible by the public or the general user population. As a user of the Service, you acknowledge and affirmatively agree 
            that in the course of using the Service you may be exposed to User Content that might be offensive, harmful, inaccurate or otherwise inappropriate. You further agree that ConnectYou shall not, under any circumstances, be liable in any way for any
            User Content.
            </li>
            <li>
            You understand that ConnectYou may provide upgraded versions of any website with receiving the notification. You also acknowledge and agree that standard carrier data charges may apply to your use of the Service including, without limitation, text messages.
            </li>
            <li>
            You shall not use any communication systems provided on our Service including, without limitation, email for any commercial or solicitation purposes. You shall not solicit for commercial purposes any users of our Service without our prior written permission.
            </li>
            <li>
            You understand and agree that you are solely responsible for compliance with any and all laws, rules, regulations, and tax obligations that may apply to your use of the Service.
            </li>
          </ul>

          <h5 class="sub_title">Accounts and Registration</h5>
          <ul class="list_of_points">
            <li>To access some features of the Service you may be required to register for an account. When you register for an account, you may be required to provide us with some information about yourself (such as your name, date of birth, email address, phone number, newsletter 
              preference or other personal information). Some of this information may be of a confidential nature and may include personal identifying information (all "Your Information").
            </li>
            <li>
            If you provide Your Information to us, then you agree to provide true, current, complete and accurate information and not to misrepresent your identity.  You also agree to keep Your Information current and to update it with any changes.
            </li>
            <li>
            Our collection, use and disclosure of Your Information is governed by this Agreement and our Privacy Policy which you may access on the website with the same name respectively.
            </li>
          </ul>

          <h5 class="sub_title">Account Management</h5>
          <ul class="list_of_points">
            <li>
            Maintaining the security of your password. If you have been provided an account by ConnectYou in linking with your usage of the Service, you are accountable for safeguarding of your password and any other credentials related to the access to your account. You, and not ConnectYou, are 
            responsible for any activity occurring in your account, whether or not you authorised that activity. If you become aware of any unauthorised access to your account, you should notify ConnetcYou immediately.
            </li>
            <li>
            Keep Your Details Accurate. ConnectYou may send notices to the email address. You must keep your email address, and, when applicable, your contact details and payment details associated with your personal account updated and accurate.
            </li>
            <li>
            We reserve the right to modify, suspend, or terminate the Service, or any user account and access to the Service for any reason, without notice, at any time, and without liability to you.
            </li>
            <li>
            You can cancel your account at any time. Upon termination or cancellation, all licenses and other rights granted to you in these Terms will instantaneously cease.
            </li>
            <li>
            ConnectYou keeps the right to refuse to provide an account or authorised access to the Service to anyone for any reason at any time.
            </li>
          </ul>

          <h5 class="sub_title">Free Account</h5>
          <ul class="list_of_points">
            <li>
            ConnectYou offers a version of its Service for free, as well as the issued set of premium, i.e. paid, Subscription Plans. The terms and prices of these Subscription Plans change from throughout the time and the most current terms and prices may be discovered on our Website.
            </li>
          </ul>
          

          <h5 class="sub_title">Subscription Trials</h5>
          <ul class="list_of_points">
            <li>
            ConnectYou might, depending on the decision and period of time, offer the paid Subscription Plan Service on a trial basis on a 3 weeks basis. If you are currently on a free trial, you may stop your paid account, free of charge, at any time until your trial period expires. (The creation day 
            establishes the first day of the trial.) Upon expiry date of the trial period, your account will automatically renew as a paid Subscription Plan/Plans for the Service and/or Services.
            </li>
            <li>
            The final day of the trial indicates the due date of the first payment. If a payment is not received by ConnectYou on the expiry date, your account will be frosen and inaccessible, and all shared links will be switched off until all outstanding payments have been processed by ConnectYou. 
            You hold the accountability for settling all outstanding balances in a timely manner and maintaining updated billing information. If you fail to do so within 45 days, your account will be deactivated and deleted from the website.
            </li>
            <li>
            Unless we notify you otherwise, if you are participating in any trial period offer, you must cancel the Service by the end of the trial period to avoid incurring charges. If you do not cancel your Service and we have told you the Service will convert to a paid subscription service at the end of 
            the trial period, you authorise us to charge you for the Service and no credits or refunds will be available. You may, however, cancel your Subscription Plan before the next billing in accordance with the terms and conditions of this Agreement.
            </li>
            <li>
            If you are a paid subscription (non-trial) user, you will not be given a refund for your most recent (or any previous) billing period.
            </li>
          </ul>

          <h5 class="sub_title">Suspension and Termination of Services</h5>
          <ul class="list_of_points">
            <li>
            By You. If you terminate a Subscription Plan (monthly or annual) in the middle of a billing cycle, you will not receive a refund for any period of time you did not use in that billing cycle unless you are terminating these Terms for any of the following reasons: (a) we have materially breached 
            these Terms and failed to cure that breach within 30 days after you have so notified us in writing; or (b) a refund is required by law.
            </li>
            <li>
            By ConnectYou. ConnectYou may terminate your Monthly Subscription Plan at the end of a billing cycle by providing at least 30 days prior written notice to you. ConnectYou may terminate your Annual Subscription Plan for any reason by providing at least 30 days written notice to you and will provide a 
            pro rata refund for any period of time you did not use in that billing cycle. ConnectYou may suspend performance or terminate your Subscription for any of the following reasons: (a) you have materially breached these Terms and failed to cure that breach within 30 days after ConnectYou has so notified you 
            in writing; (b) you cease your business operations or become subject to insolvency proceedings and the proceedings are not dismissed within 90 days; or (c) you fail to pay fees for 30 days past the due date. Additionally, ConnectYoy may limit or suspend the Service to you if you fail to comply with these Terms, 
            or if you use the Service in a way that incurs legal liability to us or disrupts others’ use of the Service. ConnectYou may also suspend the Service to you if we are investigating suspected misconduct on your part. ConnectYou will use commercially reasonable efforts to narrow the scope and duration of any limitation or 
            suspension under this Section as is needed to resolve the issue that prompted such action. ConnectYou has no obligation to retain your User Content upon termination of the applicable Service.
            </li>
            <li>
            Privacy and Your Personal Information For information about Panion data protection practices and privacy policies, please read our Privacy Policy where you have accessed these Terms of Service or on our website. This policy explains how we treat your personal information, and protect your privacy when you use the Service. 
            You agree to the use of your data in accordance with the ConnectYou Privacy Policy.
            </li>
          </ul>

          <h5 class="sub_title">Information Accuracy</h5>
          <ul class="list_of_points">
            <li>
            We make no representation as to the completeness, accuracy, or currency of any information on the Service or other content available on this Site.
            </li>
            <li>
            We attempt to ensure that information on this Service is complete, accurate and current, however, despite our best efforts, the information on our Service may occasionally be inaccurate, incomplete or out of date, and ConnectYou disclaims any responsibility or liability for such information. By using the Service, you agree 
            to accept such risks.
            </li>
          </ul>


          <h5 class="sub_title">Proprietary Rights</h5>
          <ul class="list_of_points">
            <li>
            As between ConnectYou and you, ConnectYou or its licensors own and reserve all right, title and interest in and to the Service and all hardware, software and other items used to provide the Service, other than the rights explicitly granted to you to use the Service in accordance with these Terms. No title to or ownership of any proprietary 
            rights related to the Service is transferred to you pursuant to these Terms. All rights not explicitly granted to you are reserved by ConnectYou.
            </li>
          </ul>

          <h5 class="sub_title">Intellectual Property Rights</h5>
          <ul class="list_of_points">
            <li>
            Our names, graphics, videos, logos, page headers, button icons, scripts, and service names are our trademarks or trade dress in the United Kingdom and/or other countries, and are owned by ConnectYou. You may not use the trademarks without our prior written permission.
            </li>
            <li>
            We make no proprietary claim to any third-party names, trademarks or service marks appearing on our Service. Any third-party names, trademarks, and service marks are property of their respective owners.
            </li>
            <li>
            The information, descriptions, advice, data, software and content viewable on, contained in, or downloadable from our Service (collectively, "Our Content"), including, without limitation, all text, graphics, pictures, photographs, images, videos, line art, icons and renditions, are copyrighted by, or otherwise licensed to us 
            or Our Content suppliers.
            </li>
            <li>
            We also own a copyright of a collective work in the selection, coordination, arrangement, presentation, display and enhancement of Our Content (the "Collective Work").
            </li>
            <li>
            All software used on or within our Service is our property or the property of our software vendors and is protected by United States, Sweden, and international copyright laws. Viewing, reading, printing, downloading or otherwise using Our Content and/or the Collective Work does not entitle you to any ownership or intellectual 
            property rights to Our Content, the Collective Work, or the Software.
            </li>
            <li>
            You are solely responsible for any damages resulting from your infringement of our, or any third-party’s, intellectual property rights regarding the Trademarks, Our Content, the Collective Work, the Software and/or any other harm incurred by us or our affiliates as a direct or indirect result of your copying, distributing, redistributing, 
            transmitting, publishing or using the same for purposes that are contrary to the terms and conditions of this Agreement.
            </li>
          </ul>
          

          <h5 class="sub_title">Use of Our Content</h5>
          <ul class="list_of_points">
            <li>
            We grant you a limited license to access, print, download or otherwise make personal use of Our Content and the Collective Work for your non-commercial personal use; provided, however, that you shall not delete any proprietary notices or materials with respect to the foregoing.
            </li>
            <li>
            You may not modify Our Content or the Collective Work or utilize them for any commercial purpose or any other public display, performance, sale, or rental; you may not decompile, reverse engineer, or disassemble Our Content and the Collective Work, or transfer Our Content or the Collective Work to another person or entity.
            </li>
          </ul>


          <h5 class="sub_title">User Content Rights and Related Responsibilities; License</h5>
          <ul class="list_of_points">
            <li>
            "User Content" means, without limitation, any messages, texts, reviews, digital files, images, photos, personal profile (including a photo of yourself), artwork, videos, audio, comments, feedback, suggestions, reviews and documents, or other content you upload, transmit or otherwise make available to ConnectYou and its users 
            via the Service. You represent and warrant that you own or otherwise control the rights to your User Content and agree to indemnify ConnectYou and its affiliates for all claims arising from or in connection with any claims to any rights in your User Content or any damages arising from your User Content.
            </li>
            <li>
            By submitting User Content on or through the Service, you grant ConnectYou a worldwide, non-exclusive, royalty-free license (with the right to sublicense) to use, copy, reproduce, process, adapt, modify, publish, transmit, display and distribute such User Content without attribution, and without the requirement of any permission 
            from or payment to you or to any other person or entity, in any manner including, without limitation, for commercial, publicity, trade, promotional, or advertising purposes, and in any and all media now known or hereafter devised, and to prepare derivative works of, or incorporate into other works, such User Content.
            </li>
            <li>
            In order for us to provide the Service to you, we require that you grant us certain rights with respect to User Content, including the ability to transmit, manipulate, process, store and copy User Content in order to provide our Service. Your acceptance of this Agreement gives us the permission to do so and grants us any such 
            rights necessary to provide the Service to you.
            </li>
            <li>
            You agree that any User Content you submit to our Service may be viewed by other users, any person visiting or participating in the Service and by the public in general.
            </li>
            <li>
            ConnectYou expressly disclaims any liability for the loss or damage to any User Content or any losses or damages you incur as a result of the loss or damage of any User Content. It is your responsibility to backup any User Content to prevent its loss.
            </li>
            <li>
            You are solely responsible for User Content, including, without limitation, reviews, comments and feedback, and any damages suffered by ConnectYou resulting therefrom.
            </li>
            <li>
            ConnectYou may remove or return any User Content at any time for any reason whatsoever, or for no reason at all. We are not responsible for the authenticity, accuracy, completeness, appropriateness, or legality of User Content.
            </li>
            <li>
            You represent and warrant that all information that you submit is authentic, accurate and truthful and that you will promptly update any information provided by you that subsequently becomes inaccurate, misleading or false.  
            </li>
            <li>
            User Content is not considered to be confidential. You agree not to submit User Content in which you have any expectation of privacy.
            </li>
            <li>
            ConnectYou has no control over User Content once posted, and it is possible that visitors to the App or Site may copy User Content and repost it elsewhere.
            </li>
            <li>
            You agree not to post as part of the Service any offensive, inaccurate, incomplete, abusive, obscene, profane, threatening, intimidating, harassing, racially offensive, or illegal material. The following includes, without limitation, examples of the things you may not do:
            </li>
            <li>
            Impersonate any person or entity.
            </li>
            <li>
            Stalk, harass, defame, abuse, bully, threaten or otherwise violate the legal rights of others.
            </li>
            <li>
            Advocate for, harass, or intimidate another person.
            </li>
            <li>
            Promote information that is false or misleading.
            </li>
            <li>
             Promote illegal activities or conduct that is defamatory, libellous or otherwise objectionable.
            </li>
            <li>
            Promote violence, racism, bigotry, hatred or physical harm of any kind against any group or individual.
            </li>
            <li>
            Transmit anything that exploits children or minors or that depicts cruelty to animals.
            </li>
            <li>
              Solicit personal information from anyone under the age of 18.
            </li>
            <li>
            Use the service in an illegal manner or to commit an illegal act.
            </li>
            <li>
            Transmit any material that contains software viruses, or any other computer code, files or programs designed to interrupt, destroy or limit the functionality of any computer software or hardware.
            </li>
            <li>
            Transmit any content that contains video, audio, or images of another person without his or her permission or that of their legal guardian.
            </li>
            <li>
            Promote material that exploits people in a sexual, pornographic or violent manner.
            </li>
            <li>
            Provide instructional information about illegal activities.
            </li>
            <li>
            Infringe upon someone else's trademark, copyright or other intellectual property or other rights.
            </li>
            <li>
            Promote commercial activities including without limitation sales, contests, sweepstakes, barter, advertising, business offers and competitor softwares and services.
            </li>
          </ul>


          <h5 class="sub_title">Monitoring Use Of Service, User Content, Communications</h5>
          <ul class="list_of_points">
            <li>
            ConnectYou respects your privacy, however, you are responsible for your use of the Service and for any User Content you provide or communication you engage in, including compliance with applicable laws, rules, and regulations.
            </li>
            <li>
            While we have no obligation to monitor User Content or in-Service communications, and we are not responsible for monitoring the Service for inappropriate or illegal User Content, communication or conduct by users of our Service, 
            we nevertheless have the right, in our sole discretion, to monitor, edit, refuse to post, or remove any User Content or communication.
            </li>
            <li>
            ConnectYou may also, at our sole discretion, choose to monitor and/or record your interaction or your communications with other users of the Service or the Service (including without limitation messaging, chat text and voice communications) 
            when you are using the Service, in order to prevent harm to other users, the public or to the Service.
            </li>
            <li>
            ConnectYou will not take the measures provided in this section unless we deem it necessary to prevent harm or injury to other users, the public or ConnectYou.
            </li>
            <li>
            ConnectYou disclaims any responsibility and liability for an action taken by any user resulting from any communication using the ConnectYou Service or from any User Content provided to, and posted on, the Service.
            </li>
            <li>
            We do not endorse, support or approve any opinions expressed by users, including any User Content or any communications conducted between users using the Service. All User Content and communication is the sole responsibility of the person who originated such User Content or communication.
            </li>
            <li>
            Users may report any User Content, communication or actions of users that violate any of the terms of this Agreement by contacting us.
            </li>
          </ul>


          <h5 class="sub_title">Interruption of Service</h5>
          <ul class="list_of_points">
            <li>
            Your access and use of our Service may be interrupted from time to time for any of several reasons, including, without limitation, the malfunction of equipment, periodic updating, maintenance or repair of our Service or other actions that we, in our sole discretion, may elect to take.
            </li>
            <li>
            You agree that we will not be liable to you or to any third party for any interruption of the Service or any part thereof.
            </li>
          </ul>


          <h5 class="sub_title">Third Party Links, Services and Content</h5>
          <ul class="list_of_points">
            <li>
            The Service may contain features, services and functionalities linking you or providing you with access to third party services and content, websites, directories, servers, networks, systems, information, databases, applications, software, programs, products, services, and the Internet as a whole. 
            Because we have no control over such sites and resources, we are not responsible for the availability of such external sites or resources and do not endorse and are not responsible or liable for any content, advertising, products or other materials on or available from such sites or resources. When you 
            visit or use a third party’s website, you agree to read and consent to the third party’s Terms of Service and Privacy Policy and you release us from any liability.
            </li>
            <li>
            You acknowledge that we are not responsible for any third-party content or services and that we are not an agent of any third party. Nor are we a direct party in any such transaction with a third party. Any such activities, and any terms associated with such activities, are solely between you and the 
            applicable third party. Should you have any problems resulting from your use of any third-party services, or should you suffer data loss or other losses as a result of problems with any of your other service providers or any third-party services, we will not be responsible unless the problem was the direct result of our actions.
            </li>
          </ul>


          <h5 class="sub_title">Electronic Communications</h5>
          <ul class="list_of_points">
            <li>
            Although we may choose to communicate with you by regular mail, we may also choose to communicate with you by electronic means including, without limitation, email, telephone, text, SMS, chatbot, or by posting notices on our Service.  When you use our Service, you consent to communicating with us electronically.
            </li>
            <li>
            You agree that all agreements, notices, disclosures and other communications that we provide to you electronically satisfy any legal requirement that such communications be in writing.
            </li>
            <li>
            When you use our Services, you consent to communicating with us and with other users electronically including a chat service facilitated by the App or our third-party service providers technology.  While we respect your privacy, you consent to Panion's right to review such communication in the event of a dispute between 
            you and another user, or to prevent harm or injury to another user or to Panion. 
            </li>
          </ul>

          <h5 class="sub_title">Electronic Transactions</h5>
          <ul class="list_of_points">
            <li>
            Your use of the Service includes the ability to enter into agreements, including these Terms, and to effect transactions electronically, including financial transactions and purchases. You acknowledge that your electronic submissions constitute your agreement and intent to be bound by such agreements, financial transactions and purchases.
            </li>
            <li>
            Your agreement and intent to be bound by electronic submissions applies to all records relating to all transactions you enter into on this site, including purchases, financial transactions, notices of cancellation, policies, contracts, and applications.
            </li>
            <li>
            In order to access and retain your electronic records, you may be required to have certain hardware and software, which are your sole responsibility.
            </li>
            <li>
            third-party terms of agreement when using the App Store Sourced Application.
            </li>
          </ul>


          <h5 class="sub_title">Security</h5>
          <ul class="list_of_points">
            <li>
            Violating the security of our App, Site and Service is prohibited and may result in criminal and civil liability. ConnectYou may investigate incidents involving such violations and may involve, and will cooperate with law, enforcement if a criminal violation is suspected. Security violations include, without limitation, unauthorised access to or 
            use of data or systems including any attempt to probe, scan, or test the vulnerability of the Service or to breach security or authentication measures, unauthorised monitoring of data or traffic and interference with service to any user, host, or network.
            </li>
          </ul>


          <h5 class="sub_title">Copyright and Intellectual Property Policy</h5>
          <ul class="list_of_points">
            <li>
            We respect the intellectual property rights of others. We reserve the right to remove any User Content on the Service which allegedly infringes upon another person's copyright, trademark or other intellectual property right, and/or terminate, discontinue, suspend and/or restrict the account or ability to visit and/or use the Service or remove, 
            edit, or disable any User Content on the Service which allegedly infringes upon another person's intellectual property rights. 
            </li>
            <li>
            An electronic or physical signature of a person authorised to act on behalf of the copyright owner.
            </li>
            <li>
            Identification of the copyrighted work that you claim has been infringed.
            </li>
            <li>
            Identification of the material that is claimed to be infringing and where it is located on the Service.
            </li>
            <li>
            Information reasonably sufficient to permit us to contact you, such as your address, and email address.
            </li>
            <li>
            A statement that you have a good faith belief that use of the material in the manner complained of is not authorised by the copyright owner, its agent, or law.
            </li>
            <li>
            A statement made under penalty of perjury, that the above information is accurate, and that you are the copyright owner or are authorised to act on behalf of the owner.
            </li>
          </ul>


          <h5 class="sub_title">Disclaimers; No Warranties</h5>
          <ul class="list_of_points">
            <li>
            ALL PRODUCTS AND THE SERVICE ARE PROVIDED ON AN “AS IS” AND “AS AVAILABLE” BASIS. TO THE FULL EXTENT PERMISSIBLE BY APPLICABLE LAW. PANION AND ITS PARENTS, SUBSIDIARIES, PARTNERS, AFFILIATES, OFFICERS, DIRECTORS, EMPLOYEES AND AGENTS (COLLECTIVELY, THE “PANION PARTIES”), DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE 
            IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT.
            </li>
            <li>
            WITHOUT LIMITING THE FOREGOING, WE MAKE NO WARRANTY THAT (I) THE SERVICES AND PRODUCTS WILL MEET YOUR REQUIREMENTS, (II) THE SERVICES WILL BE UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE, OR (III) THE QUALITY OF ANY PRODUCTS, SERVICES, OR INFORMATION PURCHASED OR OBTAINED BY YOU FROM US WILL MEET YOUR EXPECTATIONS.
            </li>
            <li>
            THE SERVICE CAN INCLUDE TECHNICAL OR OTHER MISTAKES, INACCURACIES OR TYPOGRAPHICAL ERRORS. FURTHERMORE, THE INFORMATION OR SERVICES ON THIS SITE OR APP MAY BE OUT OF DATE. WE MAY MAKE CHANGES TO THE PRODUCTS, INFORMATION AND SERVICES ON THIS SITE OR APP, INCLUDING THE PRICES AND DESCRIPTIONS OF ANY SERVICES OR PRODUCTS LISTED HEREIN AT ANY TIME WITHOUT NOTICE. 
            HOWEVER, WE HAVE NO OBLIGATION TO DO SO.
            </li>
            <li>
            THE CONNECTYOU PARTIES DO NOT WARRANT THAT THE SERVICE WILL BE UNINTERRUPTED OR ERROR-FREE, THAT DEFECTS WILL BE CORRECTED, THAT THE SERVICE OR THE SERVERS THAT MAKE THE SERVICE AVAILABLE WILL BE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS, OR THAT ANY PRODUCT DESCRIPTIONS OR OTHER CONTENT OFFERED AS PART OF THE SERVICE, ARE ACCURATE, RELIABLE, CURRENT OR COMPLETE.
            </li>
            <li>
            YOU EXPRESSLY AGREE THAT YOUR USE OF THE SERVICE IS AT YOUR SOLE RISK. IF YOU DOWNLOAD ANY CONTENT FROM THE SERVICE, YOU DO SO AT YOUR OWN DISCRETION AND RISK. YOU WILL BE SOLELY RESPONSIBLE FOR ANY DAMAGE TO YOUR COMPUTER SYSTEM OR LOSS OF DATA THAT RESULTS FROM THE DOWNLOAD OF ANY CONTENT THROUGH THE SERVICE.
            </li>
            <li>
            WE RESERVE THE RIGHT TO RESTRICT OR TERMINATE YOUR ACCESS TO THE SERVICE OR ANY FEATURE OR PART THEREOF AT ANY TIME.
            </li>
            <li>
            THE CONNECTYOU PARTIES ASSUME NO RESPONSIBILITY FOR THE DELETION, MISDELIVERY OR FAILURE TO STORE ANY CONTENT OR PERSONALISATION SETTINGS.
            </li>
            <li>
            SOME STATES OR OTHER JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO SOME PARTS OF THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.
            </li>
          </ul>



        </div>
        <div class="modal_footer">
          <button class="close_modal_btn">Close</button>
        </div>
      </div>
    </div>
  </div>





<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/main.js"></script>
<script>
  


</script>
</body>

</html>
