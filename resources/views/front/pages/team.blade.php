@extends('front/layouts/master')

@section('title')
	Team
@endsection

@section('content')
	<div class="page-banner team-banner">
		<div class="container">
			<div class="banner-header">
				Our Team
			</div>
		</div>
	</div>
	<div class="mt-77">
		{{-- <h3 class="text-center team-header is-blue">All</h3> <br>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<a href="#member1" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/team_1.jpg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Ms. M.O Omitowoju <br>
						    	<span class="position">CEO / Co-Founder</span>
							</p>
						  </div>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="#member2" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/Moses.jpeg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Moses Onitilo <br>
						    	<span class="position">Founder / CTO</span>
							</p>
						  </div>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="#member3" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/team_3.png') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Olusegun George <br>
						    	<span class="position">Founder / COO</span>
						    </p>

						  </div>
						</div>
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-md-4">
					<a href="#member4" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/team_4.jpg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	John Kamara <br>
						    	<span class="position">Founder / CVE</span>
							</p>
						  </div>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="#member5" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/Ayodele.jpeg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Ayodele Patrick Aderinwale, MFR. <br>
						    	<span class="position">Advisory Board Member</span>
							</p>
						  </div>
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="#member6" class="team-link" data-toggle="modal">
						<div class="card" style="width: 18rem;">
						  <img class="card-img-top" src="{{ asset('img/front/team/Richard.jpeg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Richard Mifsud <br>
						    	<span class="position">Advisory Board Member</span>
						    </p>
						  </div>
						</div>
					</a>
				</div>
			</div>
		</div> <br> <br> --}}
		<h3 class="text-center team-header is-blue">The Management Team</h3>
		<div class="container">
			<div class="row">
				<div class="col-3">
					<a href="#member2" class="team-link" data-toggle="modal">
						<div class="card">
							<img class="card-img-top" src="{{ asset('img/front/team/Moses.jpeg') }}" style="min-height: 300px;">
							<div class="card-body">
							<p class="card-text member-name text-center">
								Moses Onitilo <br>
								<span class="position">Founder / CTO</span>
							</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-3">
					<a href="#member8" class="team-link" data-toggle="modal">
						<div class="card">
							<img class="card-img-top" src="{{ asset('img/front/team/Victory.jpeg') }}" style="max-height: 300px;">
							<div class="card-body">
								<p class="card-text member-name text-center">
									Victory Yemi Oluwasegun <br>
									<span class="position">CIO</span>
							</p>
							</div>
						</div>
					</a>
				</div>
				<div class="col-3">
					<a href="#member3" class="team-link" data-toggle="modal">
						<div class="card">
						  <img class="card-img-top" src="{{ asset('img/front/team/team_3.png') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	Olusegun George <br>
						    	<span class="position">Founder / COO</span>
						    </p>
						  </div>
						</div>
					</a>
				</div>
				<div class="col-3">
					<a href="#member4" class="team-link" data-toggle="modal">
						<div class="card">
						  <img class="card-img-top" src="{{ asset('img/front/team/team_4.jpg') }}" style="max-height: 300px;">
						  <div class="card-body">
						    <p class="card-text member-name text-center">
						    	John Kamara <br>
						    	<span class="position">Founder / CVE</span>
							</p>
						  </div>
						</div>
					</a>
				</div>
				
			</div> <br> <br>
			<div class="row text-center">
					<div class="col-md-4 offset-md-4">
						<a href="#member7" class="team-link" data-toggle="modal">
							<div class="card">
								<img class="card-img-top" src="{{ asset('img/front/team/Bendon.jpeg') }}" style="min-height: 300px;">
								<div class="card-body">
									<p class="card-text member-name text-center">
										Bendon Murgor <br>
										<span class="position">Head of Software Development</span>
								</p>
								</div>
							</div>
						</a>
					</div>
			</div>
		</div> <br><br>
		<h3 class="text-center team-header is-blue">The Advisory Board</h3>
		<div class="container">
			<div class="row">
				<div class="col-md-6 offset-md-3">
					<div class="row">
						<div class="col-md-6">
							<a href="#member1" class="team-link" data-toggle="modal">
								<div class="card">
									<img class="card-img-top" src="{{ asset('img/front/team/team_1.jpg') }}" style="max-height: 300px;">
									<div class="card-body">
									<p class="card-text member-name text-center">
										Ms. M.O Omitowoju <br>
										<span class="position">Advisory Board Member</span>
									</p>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6">
							<a href="#member6" class="team-link" data-toggle="modal">
								<div class="card" style="min-height: 387px;">
								  <img class="card-img-top" src="{{ asset('img/front/team/Mifsud.jpeg') }}" style="min-height: 285px;">
								  <div class="card-body">
								    <p class="card-text member-name text-center">
								    	Richard Mifsud <br>
								    	<span class="position">Advisory Board Member</span>
								    </p>
								  </div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Member One Modal -->
	<div class="modal" id="member1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Ms. Omitowoju</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <p class="text-justify">Ms Funmi Omitowoju is armed with a wealth experience in direct sales, marketing and business development that spans over 30 years in West Africa and Nigeria. She is the former regional Director for MoneyGram International, Anglophone- West Africa and also former Managing Director of UBA Prestige Banking (the standalone personal financial services arm of UBA Plc)</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="#" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Member Two Modal -->
	<div class="modal" id="member2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Mr. Onitilo</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-justify">
	        <p>Moses Onitilo is a 25-year IT veteran with a background in ITSM, ITIL, Digital Transformation, AI, Blockchain, Encryption, Security and other leading edge enterprise management technologies.</p>
			<p>He is also the co-founder and managing Partner of Principle Technologies LLC, an 18yrs Digital Transformation, ITSM, and Development consulting company in USA, with Fortune 500 and European clients.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="https://www.linkedin.com/mwlite/me" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Member Three Modal -->
	<div class="modal" id="member3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Mr. Olusegun</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-justify">
	        <p>Olusegun George is a former Account Director, West Africa for Governance Risk and Compliance with Thompson Reuters,responsible for driving business development across the West African region and providing GRC advisory services to regulators and Operators. He has over 20 years Banking and Financial services, as former Group Head of Treasury Shared Services and Former Chief Operating officer, Global Markets, both at UBA as well as former Market Analyst at Goldman Sachs to mention a few.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="https://ng.linkedin.com/in/olusegun-george-54052163" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Member Four Modal -->
	<div class="modal" id="member4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Mr. Kamara</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-justify">
	        <p>John Kamara have over 20 years experience in business strategy, digital Technology, market growth, Gaming industry expert for various Global Markets.</p>
			<p>John is a leading expert in E-Commerce, Internet Solutions, Digital Applications, Financial Payment Solutions, Gaming (sports betting, poker,casino and lottery), AI and Blockchain solutions which have earned him AI Ambassador for City AI in Nairobi and Lagos.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="https://www.linkedin.com/in/johnkamara" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Member Five Modal -->
	<div class="modal" id="member5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Mr. Ayodele</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-justify">
	        <p>Ayodele Patrick  Aderinwale’s career has spanned politics, leadership, international relations, education, public service and philanthropy.</p>
			<p>Prior to his appointment as the Deputy Chief Coordinator, OOPL, Mr. Aderinwale served as the Executive Director of Africa Leadership Forum, ALF, Africa’s premier civil society organization founded by Nigeria’s former President Olusegun Obasanjo out of the need to assist in improving the capacity and competence of African leaders to tackle development and leadership challenges confronting post independence Africa.  At ALF, Mr. Aderinwale began several programs such as the Regional African Parliamentarians Conference, the Africa Women's Forum, the Legislative Internship Programme and the Democratic Leadership Training Workshop. He helped create the Conference on Stability, Security, Development and Cooperation in Africa (CSSDCA) which was adopted by the African Union in 2002.</p>
			<p>He has also been a consultant for several international agencies, including the United Nations, European Union and the former Organization of Africa Unity now known as the African Union. He participated in the initial drafting of the Millennium Plan for Africa (MAP) which later became the New Partnership for Africa's Development (NEPAD) and was an inaugural member of the Nigeria Steering Committee of the Africa Peer Review Mechanism (APRM).</p>
			<p>In 2006, Mr. Aderinwale was conferred with the national honour of Member of the Federal Republic of Nigeria (MFR) by President Olusegun Obasanjo (GCFR). In his honour, his classmates and colleagues launched a foundation, the Ayodele Aderinwale Foundation for Education and Leadership in Africa (AAFELA), which provides scholarships to young boys and girls in schools across the country.</p>
			<p>Mr. Aderinwale also currently serves on the boards of various organizations.</p>
			<p>Aderinwale was born in Osogbo, Osun State, Nigeria. He attended the University of Lagos for his undergraduate and graduate studies in Political Science respectively. He is also an alumnus of the United Nations University's International Leadership Academy, and of Harvard University's  John F Kennedy School of Government’s Executive Programme for Leaders in Development.</p>
			<p>He is happily married to Mrs. Tosin Aderinwale and they are blessed with two sons and a girl. </p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="https://www.linkedin.com/in/ayodele-aderinwale-a45259103" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Member Six Modal -->
	<div class="modal" id="member6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">About Mr. Richard</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body text-justify">
	        <p>Richard Mifsud is currently the CEO and Co-Founder of Helio Gaming, a leading iGaming
							games provider focusing mainly on innovative Lottery products. Richard is an experienced
							executive within the igaming & technology industry internationally and sits on several
							boards across a number of industries. During his more than 15 years career he has held
							several managerial positions where he also holds a personal management license from UK
							gambling commission.
							</p>
			<p>Richard has high aspirations about Africa as a continent and as a result he has co-founded a
					company called Afrisors. A company based in Malta with offices worldwide which is assisting
					existing or new businesses to operate in several Sub-Saharan regions. The core business
					focus of Afrisors is iGaming, Blockchain and Fintech.
					</p>
			<p>He is also an advisor and consultant on a number of sub-saharan projects including
					Nurucoin in Kenya, Jamborow in Nigeria and ShellPay in Malta, China & Africa.
					</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <a href="https://www.linkedin.com/in/richard-mifsud-b268a79" class="btn btn-primary">View Profile</a>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="modal" id="member7" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">About Mr. Bendon</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body text-justify">
						<p>With more than 13 Years of experience in End User Support, Strong Systems Administration, Software Engineering and Integration, Networks Administration and IT Project Management work.</p>
						<p>Bendon has certifications in CCNA, ITILv3 and GCIH, with extensive experience in Gaming, USSD Development, Mobile Money integrations, Blockchain, Mobile Applications and Systems Security.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<a href="https://www.linkedin.com/in/bendonmurgor/" class="btn btn-primary">View Profile</a>
					</div>
				</div>
			</div>
		</div>

		<div class="modal" id="member8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">About Mr. Oluwasegun</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body text-justify">
							<p>Victory Yemi Oluwasegun was Managing Partner at Trustmark Ventures, where he lead all IT initiatives, which include managing product development, software and hardware engineering, and directing technical integration and operations.</p>
							<p>During his 25+ year career, Victory has held several senior management positions with technology companies, including Infinite E Group and CommerceSolve Technologies. In addition to his experience as the CTO for Faith Technology Group, one of the then fastest growing technology operators for SMB sector. Victory has broad and diverse industry experience, having also worked as Subject-Matter-Expert for Microsoft, NASA and the Office of the Chief Technologist, Washington, DC under the auspices of Lockheed Martin IT. He has successfully managed organizations that have delivered technology solutions to the automotive, computer, engineering, manufacturing, financial services, retail industries, and all levels of Government. </p>
							<p>In 2008, he was given commendation from the US State Department – Overseas Embassy Operation as one of the Best Contractors to serve the State with distinction. Victory has a degree in Computer Information Systems – Network Engineering and a Microsoft Certified Trainer.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<a href="#" class="btn btn-primary">View Profile</a>
						</div>
					</div>
				</div>
			</div>
	<br> <br>
@endsection
