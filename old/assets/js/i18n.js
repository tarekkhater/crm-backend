
/**
 * Simple site-wide i18n for static HTML (EN/HU).
 * How it works:
 * - Add data-i18n="key" to any element whose text should be translated.
 * - Include this script on every page (ideally before </body>).
 * - Use the language switcher: any element with [data-setlang="en|hu"] will switch language.
 * - Language is persisted in localStorage and auto-applied on each page.
 * - You can also force a language via URL: ?lang=en or ?lang=hu
 */

(function () {
  const DEFAULT_LANG = "hu";

  // Map of translations. Extend freely.
  const dict = {
    en: {
      nav_home: "Home",
      nav_about: "About",
      nav_accounts: "Accounts",
      nav_vip: "VIP",
      nav_education: "Education",
      nav_details: "Details",
      nav_markets: "Markets",
      nav_services: "Services",
      nav_features: "Features",
      nav_plans: "Plans",
      nav_articles: "Articles",
      nav_contact: "Contact",
      btn_get_started: "Get Started",
      btn_register: "Register",

      art1: "20 Best Forex Trading Tools for 2023",
      art2: "3 Pivot Point Forex Trading Strategies",
      art3: "How to Become a Forex Trader",
      art4: "Top Reasons Why Forex Traders Fail and Lose Money",

      hero_title: "Plan Your future with <span>Quantum Prime</span>",
      hero_sub: "Get your financial Freedom",

      accounts_title: "Accounts",
      accounts_sub: "Accounts & Plans",
      advantages: "Advantages",
      adv_item_1: "The work of the international currency market",
      adv_item_2: "Indicators",
      adv_item_3: "Web platform and client terminals",
      adv_item_4: "News analysis",
      adv_item_5: "Technical and fundamental analysis (Individual)",
      adv_item_6: "Effective Trading systems and methods",
      adv_item_7: "The basics of graphic analysis and market patterns",
      adv_item_8: "Auto Trading",

      trading_ops: "Our trading opportunities",
      ops_1: "You will trade on the live market",
      ops_2: "It is for free, and you can use it as long as you want",
      ops_3: "It simulates real trading conditions, but does not expose you to risk",
      ops_4: "You trade with virtual currency – with no danger of losing real money",
      ops_5: "You can test all possible trading strategies as many times as you want",
      ops_6: "You can learn to read charts, follow market trends, open and close orders",

      plans_title: "Plans",
      plans_sub: "Check our Trading Plans",
      plan_silver: "SILVER",
      plan_gold: "GOLD",
      plan_platinum: "PLATINUM",
      price_3000: "$3000",
      price_5000: "$5000",
      price_10000: "$10000",
      leverage_1_1: "Leverage 1:1",
      monthly_profits_range_1: "Monthly Profits 12.2% - 22.3%",
      monthly_profits_range_2: "Monthly Profits 23.4% - 34.6%",
      monthly_profits_range_3: "Monthly Profits Up to 35% - 75%",
      withdrawal_fee_05: "Withdrawal Fee 0.5%",
      withdrawal_fee_0: "Withdrawal Fee 0%",
      trades_with_manager: "Trades With Personal account manager",
      risk_free_program: "Risk Free Trade Program",
      trades_2_4: "Trades 2-4 daily",
      trades_4_5: "Trades 4-5 daily",
      trades_6_10: "Trades 6-10 daily",

      // VIP Page translations
      vip_hero_title: "Make the most of your trading with <span>Quantum Prime</span>",
      vip_hero_subtitle: "Get more benefits and take your investing to the next level. Reach 1000$ of the total deposit amount to become a VIP.",
      vip_hero_cta: "GET VIP",
      vip_assets: "assets",
      vip_cashback: "cashback",
      vip_profitability: "profitability",
      vip_bonus: "deposit bonus",
      vip_hero_sub: "MORE REASONS TO BECOME",
      vip_reason_1_number: "1 REASON",
      vip_reason_1_title: "Personal manager",
      vip_reason_1_text: "Enjoy a personal approach to any question you have. The manager will provide you with:",
      vip_reason_1_point_1: "Individual consultations on trading and market situation to improve your results.",
      vip_reason_1_point_2: "Special offers and bonuses to make trading more successful and interesting.",
      vip_reason_1_point_3: "New trading strategies and recommendations.",
      vip_reason_2_label: "2 REASON",
      vip_reason_2_heading: "Compensation of losses",
      vip_reason_2_description: "Unsuccessful trades with VIP status are not that scary anymore. Each week you can get 10% of your weekly losses as a cashback. Use the formula to calculate how much you can return:",
      vip_reason_2_formula: "Cashback = (deposit - withdrawal - account balance) × 10%",
      vip_reason_3_number: "3 REASON",
      vip_reason_3_title: "Investment insurance",
      vip_reason_3_text: "Trading is connected with a lot of risks, that's why we got you covered. Protect your investments with a free insurance program.",
      vip_reason_3_point_1: "Trading is connected with a lot of risks, that's why we got you covered. Protect your investments with a free insurance program. If your balance reaches zero we will return part of your investments with real or bonus funds.",
      vip_reason_4_number: "4 REASON",
      vip_reason_4_heading: "Special offers",
      vip_reason_4_text: "You will constantly get exclusive offers and gifts that can potentially increase your profit:",
      vip_reason_4_point_1: "Extra funds for risk-free trades.",
      vip_reason_4_point_2: "Deposit bonuses of up to 200% on your balance.",
      vip_reason_4_point_3: "Invitation to free private tournaments with big prizes.",

      // Education Page translations
      education_hero_title: "Learn Trading with <span>LANCASTER CHAMBER</span>",
      education_hero_subtitle: "Get your knowledge from LANCASTER CHAMBER",
      education_section_subtitle: "Start Learning With <span class=\"fw-bolder text-success\"> LANCASTER CHAMBER</span>",

      // Education Tabs
      edu_tab_beginner: "Beginner",
      edu_tab_intermediate: "Intermediate", 
      edu_tab_advanced: "Advanced",

      // Beginner Tab
      edu_beginner_title: "Everything you need to start trading",
      edu_beginner_text: "Are you a beginner trader and trying to figure out where to start? Then you've found the right place! Here we've hand-picked our top educational resources for new traders, including articles, courses and webinars, so just choose your preferred way to learn to get started! Not a beginner trader? Then choose our intermediate or advanced levels for custom-tailored content for you.",

      // Intermediate Tab  
      edu_intermediate_title: "Take your trading to the next level",
      edu_intermediate_text: "You are already familiar with the trading basics - you can use our LANCASTER CHAMBER Web Trader, you have a trading strategy, and your investments are starting to pay off. But how can you take your trading to the next level? Here we've collected our best intermediate trading education content for you, to help you make the leap from beginner to advanced - almost entirely online, and 100% free. Not an intermediate trader? Then choose our beginner or advanced levels for custom-tailored content for you.",

      // Advanced Tab
      edu_advanced_title: "Advanced education for experienced traders", 
      edu_advanced_text: "If you're on this page, you're already a profitable trader. You have a strategy that works, you know how to get the best out of your trading platform, and your risk management sees your portfolio growing steadily. What's next? That's what this page covers. Including topics such as trading automation and advanced portfolio management, our advanced education topics are designed to help you get the best possible trading results. Not an advanced trader? Then choose our beginner or intermediate levels for custom-tailored content for you.",

      // Educational Articles
      edu_article_1_title: "Why Forex Traders Lose Money | Top Reasons",
      edu_article_1_text: "Financial trading, including the currency markets, requires long and detailed planning on multiple levels. Trading cannot commence without a trader's understanding of the market basics, and ongoing analysis of the ever-changing market environment. For those interested in investing and trading, read through the suggestions below and you will learn how to avoid losing money in Forex trading.",

      edu_article_2_title: "Poor Risk Management",
      edu_article_2_text: "Improper risk management is a major reason why Forex traders tend to lose money quickly. It's not by chance that trading platforms are equipped with automatic take-profit and stop-loss mechanisms. Mastering them will significantly improve a trader's chances for success. Traders not only need to know that these mechanisms exist, but also how to implement them properly in accordance with the market volatility levels predicted for the period, and for the duration of a trade. Keep in mind that a 'stop-loss to low' could liquidate what could have otherwise been a profitable position. At the same time, a 'take-profit to high' might not be reached due to a lack of volatility. Paying attention to risk/reward ratios is also an important part of good risk management.",

      edu_article_3_title: "Not Adapting to Market Conditions",
      edu_article_3_text: "Assuming that one proven trading strategy is going to be enough to produce endless winning trades is another reason why Forex traders lose money. Markets are not static. If they were, trading them would have been impossible. Because the markets are ever-changing, a trader has to develop an ability to track down these changes and adapt to any situation that may occur.<br/><br/>The good news is that these market changes present not only new risks but also new trading opportunities. A skillful trader values changes, instead of fearing them. Among other things, a trader needs to familiarize themselves with tracking average volatility following financial news releases and being able to distinguish a trending market from a ranging market.<br/><br/>Market volatility can have a major impact on trading performance. Traders should know that market volatility can spread across hours, days, months, and even years. Many trading strategies can be considered volatility-dependent, with many producing less effective results in periods of unpredictability. So a trader must always make sure that the strategy they use is consistent with the volatility that exists in the present market conditions.<br/><br/>Financial news releases are also important to keep track of, even if a selected strategy is not based on fundamentals. Monetary policy decisions, such as a change in interest rates, or even surprising economic data concerning unemployment or consumer confidence can shift market sentiment within the trading community.<br/><br/>As the market reacts to these events, there's an inevitable impact on supply and demand for respective currencies. Lastly, the inability to distinguish trending markets from ranging markets often results in traders applying the wrong trading tools at the wrong time.",

      edu_article_4_title: "What is the Risk Return Ratio?",
      edu_article_4_text: "The Risk/Reward Ratio (or Risk Return Ratio/RR) is simply a set measurement to help traders plan how much profit will be made should a trade progress as anticipated, or how much will be lost in case it doesn't. Consider this example. If your 'take-profit' is set at 100 pips and your stop-loss is at 50 pips, the risk/reward ratio is 2:1. This also means that you will break even at least every one out of three trades, providing that they are profitable. Traders should always check these two variables in tandem to ensure they fit with profit goals. The best way to avoid risks completely in Forex trading is to use a risk-free demo trading account. With a demo account, you can trade without putting your capital at risk, while still using the latest real-time trading information and analysis. It's the best place for traders to learn how to trade, and for advanced traders to practice their new strategies. If you are interested in giving it as go, click the banner below!",

      // Additional translations
      edu_read_more_articles: "Read More Articles",
      edu_intermediate_articles_title: "Intermediate Articles", 
      edu_intermediate_articles_text: "Here are our top 6 articles for intermediate traders, featuring the best tools and techniques to help you optimise your trading strategy and manage your time more effectively.<br/>Looking for something else? Then view all articles.",

      // Accounts Page translations
      accounts_hero_title: "Learn Trading with <span>Quantum Prime Finance CHAMBER</span>",
      accounts_hero_subtitle: "Get your knowledge from Quantum Prime Finance CHAMBER",

      // Article 1 Page translations - "20 Best Forex Trading Tools for 2023"
      art1_page_title: "Education",
      art1_page_subtitle: "20 Best Forex Trading Tools for 2023",
      
      art1_section1_title: "How to find trading opportunities",
      art1_section1_text: "This is the first group of tools for Forex trading. Forex traders use some tools to find trading opportunities. Our list of the top Forex trading tools for finding trading opportunities includes tools in the following subcategories:",
      art1_section1_item1: "Forex calendars",
      art1_section1_item2: "Trading news feeds", 
      art1_section1_item3: "Technical analysis tools",
      art1_section1_item4: "Idea-generation tools",

      art1_section2_title: "Forex calendars: Economic calendars, news calendars, corporate calendars and more",
      art1_section2_text: "Forex calendars are a valuable tool for learning about what is going to happen in the market, and planning your trades accordingly.<br/><br/>They largely target traders who want to keep up with the fundamental updates on the FX market. However, these calendars may come in handy to all types of traders.<br/><br/>Admirals free Forex calendar lists upcoming fundamental events, and releases of economic news, listed along with their previous and expected values. As soon as the news is released (sometimes, with a slight delay), the calendar is updated with proper values, and the market starts to experience new moves.",

      art1_section3_title: "Live FX news: Trading news, live market news, Forex news feeds and more",
      art1_section3_text: "Along with economic calendars, live news is another useful Forex trading tool. The problem is that there's just so much information to sift through! While you can manually search Bloomberg, Reuters, Forex Factory and FX street, wouldn't it be easier if it was all delivered to you automatically?<br/><br/>The good news is that this is possible. In fact, the Admirals MetaTrader Supreme Edition plugin includes a feature called Admiral Connect, which delivers live trading news direct to your MetaTrader terminal!<br/><br/>Admiral Connect tool connects all big news providers, including a trade analysis feature and your own RSS-feeds to display in-platform and spend less time outside the terminal when looking for additional functionality and information required for trading.",

      art1_section4_title: "Technical analysis for Forex trading",
      art1_section4_text: "Many traders see the appeal of technical analysis, as it allows them to identify FX trading opportunities without having to keep up to date with market news. The main challenge, though, is identifying accurate patterns that generate reliable trading signals.<br/><br/>The good news is that many available tools for Forex trading do the work for you.<br/><br/>The first one we'll cover is in the Technical Analysis and Trading Signals feature in Admirals' Premium Analytics portal. Powered by Trading Central's award-winning technology, this widget combines actionable technical analysis on virtually every financial instrument (including stocks and Forex) to help investors optimise their trading strategies.",

      // Article 2 Page translations - "3 Pivot Point Forex Trading Strategies"
      art2_page_title: "Education",
      art2_page_subtitle: "3 Pivot Point Forex Trading Strategies",
      
      art2_section1_title: "What is a Pivot Point?",
      art2_section1_text: "What is a pivot point in Forex? Pivot points assist traders with determining price movements in financial markets.<br/><br/>Put simply, a pivot point is a price level that is used by professional traders to assess whether prices are bullish or bearish. Pivot points represent the averages for the highs, the lows, and the closing prices that occur within a trading session or a trading day. Pivot Points are a type of indicator used for technical analysis, which provides the basis for determining market trends.",

      art2_section2_title: "Support & Resistance Levels in Pivot Point Trading",
      art2_section2_text: "Underpinning nearly all forms of technical analysis are the core concepts of support and resistance. These can be thought of as levels that are expected to be key battlegrounds in the battle between bears and bulls. As the market approaches them, some traders expect the price to rebound.<br/><br/>Others might anticipate the chance of a breakout. Consequently, they are important prices because they signpost the chance of significant movement. Therefore, identifying where these levels lie is a very useful skill to develop.<br/><br/>Given the importance of support and resistance points, there follows a natural question: How do we calculate where to find these crucial price levels? There are a large number of methods that attempt to satisfy this query.<br/><br/>One popular technique used is looking at pivot points. Pivot point trading takes standard price information, such as highs, lows and closes, and uses this information to project possible support and resistance levels.<br/><br/><strong>Free Live Trading Webinars with Admirals</strong><br/><br/>If you're interested in learning more about the best trading indicators, the most popular strategies, the latest news, trends and developments in the markets and more, there's no better way to do it than with the Admirals FREE regular trading webinars.<br/><br/>Receive step-by-step guides on how to use the best strategies and indicators, and receive expert opinions on the latest developments in the live markets. Click the banner below to register for FREE trading webinars!",

      art2_section3_title: "Forex Trader: An Introduction",
      art2_section3_text: "A trader is someone who places orders on the financial market. This could be on behalf of financial institutions, such as big banks, investment funds and hedge funds, or as an independent trader.<br/><br/>Exchange orders, such as buying or selling stocks, are either in the trader's own name, or on behalf of clients or for the financial institution or broker that employs them. There can be further categorisation, depending on the assets being traded: Forex, equities, bonds, commodities, etc.<br/><br/>Traders who work for financial institutions or brokers buy and sell shares on behalf of their employer's clients, not with their own money. This means that rather than making a profit or a loss on their actual trading, they earn a salary as a trader. In this case, the trader takes virtually no risk in the market - it is on their customer buying or selling financial instruments to cover the risk. The trader's clients may be anything from individuals to companies that do not have a trading room of their own.<br/><br/>Those who trade on their own personal account are using their own money to attempt to earn profit for themselves. These accounts are funded with their personal funds and trades are executed through online trading platforms. Even though online brokers offer leverage, the amounts traded by home traders are much smaller than those of a professional trader. Since online trading is often done on the OTC (Over the Counter) market, the success of traders in their own accounts are only estimates.",

      // Article 3 Page translations - "How to Become a Forex Trader"
      art3_page_title: "Education",
      art3_page_subtitle: "How to Become a Forex Trader",
      
      art3_section1_title: "Forex Trader: An Introduction",
      art3_section1_text: "A trader is someone who places orders on the financial market. This could be on behalf of financial institutions, such as big banks, investment funds and hedge funds, or as an independent trader.<br/><br/>Exchange orders, such as buying or selling stocks, are either in the trader's own name, or on behalf of clients or for the financial institution or broker that employs them. There can be further categorisation, depending on the assets being traded: Forex, equities, bonds, commodities, etc.<br/><br/>Traders who work for financial institutions or brokers buy and sell shares on behalf of their employer's clients, not with their own money. This means that rather than making a profit or a loss on their actual trading, they earn a salary as a trader. In this case, the trader takes virtually no risk in the market - it is on their customer buying or selling financial institutions to cover the risk. The trader's clients may be anything from individuals to companies that do not have a trading room of their own.<br/><br/>Those who trade on their own personal account are using their own money to attempt to earn profit for themselves. These accounts are funded with their personal funds and trades are executed through online trading platforms. Even though online brokers offer leverage, the amounts traded by home traders are much smaller than those of a professional trader. Since online trading is often done on the OTC (Over the Counter) market, the success of traders in their own accounts are only estimates.",

      art3_section2_title: "Defining Success as a Forex Trader",
      art3_section2_intro: "Now that you know what a trader is, how can you become a forex trader? And then, how do you become successful at it?<br/><br/>When starting to trade, it is important to understand what you want to achieve from it, and how you define success.",
      art3_section2_heading: "Things to keep in mind when you start trading Forex:",
      art3_section2_point1: "Set yourself a realistic and quantifiable goal. This could be something along the lines of, achieving a 20% annual return on your investment, or reaching a total of 100 pips per month.",
      art3_section2_point2: "Your goal should also be easy to measure.",
      art3_section2_point3: "Set a goal that can be achieved over a long time frame - it is recommended to set an annual goal to achieve rather than a monthly goal.",
      art3_section2_middle: "Once you have set your main trading goal for the year, it is now time to start learning how to achieve it.",
      art3_section2_question1: "Identify what resources are available to you.",
      art3_section2_question2: "How much money are you able to use as a starting deposit?",
      art3_section2_question3: "Do you want to become a full time Forex trader? Or are you just looking to trade on the weekends?",
      art3_section2_conclusion: "These are some of the questions you should be asking yourself.<br/><br/>Once you have a clear vision, it is time to make your action plan. This plan should include the currency pairs you are planning to trade and the number of daily trades you are going to commit to.<br/><br/>This can feel a bit overwhelming for new traders, so the good news is that in this article we share our top 10 tips to help you learn how to become a successful Forex trader.<br/><br/>If you are a beginner trader looking for a place to learn the ins and outs of Forex trading, feel free to tune into our live and free webinars by clicking the banner below:",

      footer_links: "Useful Links",
      footer_home: "Home",
      footer_about: "About us",
      footer_markets: "Markets",
      footer_services: "Services",
      footer_accounts: "Accounts",
      footer_vip: "Vip",
      footer_education: "Education",
      footer_features: "Features",
      footer_plans: "Plans",
      footer_terms: "Terms of service",
      footer_privacy: "Privacy policy",
      risk_disclosure: "Risk disclosure",
      risk_text: `Investing in high-risk groups: (Forex) and contracts for difference (CFD) is a speculative transaction with high risk, which is not suitable for every investor. You may incur partial or total loss of your investment, so we do not advise you to invest capital that you cannot risk. You should be aware of the increased risk associated with leverage. We strongly recommend that you familiarize yourself with the terms and services of our website before you start using our service.<br /><br /><span class="text-success fw-bold">Quantum Prime</span> services are operated by <span class="fw-bold">Zenit Holding Zrt.</span><br/> <span class="fw-bold">Zenit Holding </span> <span>Investment Private Limited Company </span> <br /> <span class="fw-bold"> Registered Office:</span> 1052 Budapest, Károly körút 10., Hungary
Company Registration No.: 01-10-046016 | Tax No.: 14099230241.<br /> <span> Main Activity: Asset Management (Holding Company)
Founded: 2007</span>`,
// --- AUTO-GENERATED (articles) ---

},

    hu: {
      nav_home: "Főoldal",
      nav_about: "Rólunk",
      nav_accounts: "Számlák",
      nav_vip: "VIP",
      nav_education: "Oktatás",
      nav_details: "Részletek",
      nav_markets: "Piacok",
      nav_services: "Szolgáltatások",
      nav_features: "Funkciók",
      nav_plans: "Csomagok",
      nav_articles: "Cikkek",
      nav_contact: "Kapcsolat",
      btn_get_started: "Kezdés",
      btn_register: "Regisztráció",

      art1: "A 20 legjobb Forex kereskedési eszköz 2023-hoz",
      art2: "3 Pivot Point Forex kereskedési stratégia",
      art3: "Hogyan válj Forex kereskedővé",
      art4: "A fő okok, amiért a Forex kereskedők veszítenek pénzt",

      hero_title: "Tervezd meg jövődet a <span>Quantum Prime</span> segítségével",
      hero_sub: "Szerezd meg pénzügyi szabadságod",

      accounts_title: "Számlák",
      accounts_sub: "Számlák és Csomagok",
      advantages: "Előnyök",
      adv_item_1: "A nemzetközi valutapiac működése",
      adv_item_2: "Mutatók",
      adv_item_3: "Webplatform és ügyfél terminálok",
      adv_item_4: "Hírelemzés",
      adv_item_5: "Műszaki és fundamentális elemzés (egyéni)",
      adv_item_6: "Hatékony kereskedési rendszerek és módszerek",
      adv_item_7: "A grafikus elemzés és piaci mintázatok alapjai",
      adv_item_8: "Automatikus kereskedés",

      trading_ops: "Kereskedési lehetőségeink",
      ops_1: "Valós piacon fog kereskedni",
      ops_2: "Ingyenes, és tetszés szerint használható",
      ops_3: "Valós kereskedési feltételeket szimulál, de nem teszi ki kockázatnak",
      ops_4: "Virtuális valutával kereskedik – valódi pénz elvesztésének veszélye nélkül",
      ops_5: "Minden lehetséges kereskedési stratégiát tetszés szerint tesztelhet",
      ops_6: "Megtanulhatja a grafikonok olvasását, piaci trendek követését, rendelések nyitását és zárását",

      plans_title: "Csomagok",
      plans_sub: "Tekintse meg kereskedési csomagjainkat",
      plan_silver: "EZÜST",
      plan_gold: "ARANY",
      plan_platinum: "PLATINA",
      price_3000: "$3000",
      price_5000: "$5000",
      price_10000: "$10000",
      leverage_1_1: "Tőkeáttétel 1:1",
      monthly_profits_range_1: "Havi profit 12,2% – 22,3%",
      monthly_profits_range_2: "Havi profit 23,4% – 34,6%",
      monthly_profits_range_3: "Havi profit akár 35% – 75%",
      withdrawal_fee_05: "Kifizetési díj 0,5%",
      withdrawal_fee_0: "Kifizetési díj 0%",
      trades_with_manager: "Kereskedés személyes számlakezelővel",
      risk_free_program: "Kockázatmentes kereskedési program",
      trades_2_4: "2–4 napi kereskedés",
      trades_4_5: "4–5 napi kereskedés",
      trades_6_10: "6–10 napi kereskedés",

      footer_links: "Hasznos linkek",
      footer_home: "Főoldal",
      footer_about: "Rólunk",
      footer_markets: "Piacok",
      footer_services: "Szolgáltatások",
      footer_accounts: "Számlák",
      footer_vip: "Vip",
      footer_education: "Oktatás",
      footer_features: "Funkciók",
      footer_plans: "Csomagok",
      footer_terms: "Szolgáltatási feltételek",
      footer_privacy: "Adatvédelmi irányelvek",
      risk_disclosure: "Kockázatnyilatkozat",
      risk_text: `A magas kockázatú eszközökbe, például a Forex-be és a különbözeti szerződésekbe (CFD) való befektetés spekulatív tranzakció magas kockázattal, amely nem megfelelő minden befektető számára. Részleges vagy teljes veszteség érheti befektetését, ezért nem javasoljuk, hogy olyan tőkét fektessen be, amit nem engedhet meg magának, hogy elveszítse. Tisztában kell lennie a tőkeáttétellel járó fokozott kockázattal. Határozottan ajánljuk, hogy ismerkedjen meg weboldalunk feltételeivel és szolgáltatásaival, mielőtt elkezdené szolgáltatásaink használatát.<br /><br /><span class="text-success fw-bold">Quantum Prime</span> szolgáltatásait a <span class="fw-bold">Zenit Holding Zrt.</span> üzemelteti. <br/> <span class="fw-bold">Zenit Holding </span> <span>Befektetési Zártkörűen Működő Részvénytársaság. </span><br/> <span class="fw-bold"> Székhely:</span> 1052 Budapest, Károly körút 10.
        Cégjegyzékszám: 01-10-046016 | Adószám: 14099230241.<br /> <span> Fő tevékenység: Vagyonkezelés (holding)
        Alapítva: 2007</span>`,
      // VIP Page Hungarian translations
      vip_hero_title: "Tedd ki kereskedésed a maximumot a <span>Quantum Prime</span> segítségével",
      vip_hero_subtitle: "Szerezz több előnyt és emeld befektetéseidet magasabb szintre. Érj el 1000$ összletétet, hogy VIP legyél.",
      vip_hero_cta: "LÉGY VIP",
      vip_assets: "eszközök",
      vip_cashback: "visszatérítés",
      vip_profitability: "jövedelmezőség",
      vip_bonus: "betéti bónusz",
      vip_hero_sub: "TÖBB OK, HOGY VÁLJ VIP-VÁ",
      vip_reason_1_number: "1 OK",
      vip_reason_1_title: "Személyes menedzser",
      vip_reason_1_text: "Élvezd az egyéni megközelítést minden kérdésedhez. A menedzser biztosítja neked:",
      vip_reason_1_point_1: "Egyéni konzultációk a kereskedésről és piaci helyzetről az eredmények javítása érdekében.",
      vip_reason_1_point_2: "Különleges ajánlatok és bónuszok, hogy a kereskedés sikeresebb és érdekesebb legyen.",
      vip_reason_1_point_3: "Új kereskedési stratégiák és ajánlások.",
      vip_reason_2_label: "2 OK",
      vip_reason_2_heading: "Veszteségek kompenzálása",
      vip_reason_2_description: "A sikertelen kereskedések VIP státusszal már nem annyira ijesztőek. Minden héten megkaphatod a heti veszteségeid 10%-át visszatérítésként. Használd a képletet, hogy kiszámítsd, mennyit kaphatsz vissza:",
      vip_reason_2_formula: "Visszatérítés = (betét - kifizetés - számlaegyenleg) × 10%",
      vip_reason_3_number: "3 OK",
      vip_reason_3_title: "Befektetési biztosítás",
      vip_reason_3_text: "A kereskedés sok kockázattal jár, ezért védelmet biztosítunk. Védd befektetéseidet ingyenes biztosítási programunkkal.",
      vip_reason_3_point_1: "A kereskedés sok kockázattal jár, ezért védelmet biztosítunk. Védd befektetéseidet ingyenes biztosítási programunkkal. Ha az egyenleged nullára esik, a befektetéseid egy részét valódi vagy bónusz forrásokkal visszakapod.",
      vip_reason_4_number: "4 OK",
      vip_reason_4_heading: "Különleges ajánlatok",
      vip_reason_4_text: "Rendszeresen kapni fogsz exkluzív ajánlatokat és ajándékokat, amelyek potenciálisan növelhetik profitodat:",
      vip_reason_4_point_1: "Extra források kockázatmentes kereskedésekhez.",
      vip_reason_4_point_2: "Betéti bónuszok akár 200%-ig az egyenlegedről.",
      vip_reason_4_point_3: "Meghívó ingyenes privát tornákra nagy díjakkal.",

      // Education Page Hungarian translations
      education_hero_title: "Tanulj meg kereskedni a <span>LANCASTER CHAMBER</span> segítségével",
      education_hero_subtitle: "Szerezz tudást a LANCASTER CHAMBER-től",
      education_section_subtitle: "Kezdd el a tanulást a <span class=\"fw-bolder text-success\"> LANCASTER CHAMBER</span> segítségével",

      // Education Tabs Hungarian
      edu_tab_beginner: "Kezdő",
      edu_tab_intermediate: "Középhaladó", 
      edu_tab_advanced: "Haladó",

      // Beginner Tab Hungarian
      edu_beginner_title: "Minden, amire szükséged van a kereskedés megkezdéséhez",
      edu_beginner_text: "Kezdő kereskedő vagy, és próbálod kitalálni, hol kezdjed? Akkor jó helyen jársz! Kiválogattuk számodra a legjobb oktatási anyagokat új kereskedők számára, beleértve cikkeket, tanfolyamokat és webináriumokat, tehát egyszerűen válaszd ki a preferált tanulási módod, hogy elkezdhesd! Nem vagy kezdő kereskedő? Válaszd a középhaladó vagy haladó szintünket, hogy a Te igényeidhez igazított tartalmat kapj.",

      // Intermediate Tab Hungarian
      edu_intermediate_title: "Emeld kereskedésed magasabb szintre",
      edu_intermediate_text: "Már ismered a kereskedés alapjait - tudod használni a LANCASTER CHAMBER Web Trader platformunkat, van kereskedési stratégiád, és a befektetéseid elkezdenek megtérülni. De hogyan emelheted kereskedésed magasabb szintre? Összegyűjtöttük számodra a legjobb középhaladó szintű oktatási anyagokat, hogy segítsünk átlépni a kezdő szintről a haladóra - szinte teljesen online és 100% ingyen. Nem vagy középhaladó kereskedő? Válaszd a kezdő vagy haladó szintünket, hogy a Te igényeidhez igazított tartalmat kapj.",

      // Advanced Tab Hungarian
      edu_advanced_title: "Haladó oktatás tapasztalt kereskedőknek", 
      edu_advanced_text: "Ha ezen az oldalon vagy, már jövedelmező kereskedő vagy. Van egy működő stratégiád, tudod, hogyan használd ki legjobban a kereskedési platformodat, és a kockázatkezelés révén portfóliód folyamatosan növekszik. Mi a következő lépés? Erről szól ez az oldal. A kereskedési automatizálás és haladó portfóliókezelés témáit is magába foglaló haladó oktatási témáink célja, hogy segítsenek elérni a lehető legjobb kereskedési eredményeket. Nem vagy haladó kereskedő? Válaszd a kezdő vagy középhaladó szintünket, hogy a Te igényeidhez igazított tartalmat kapj.",

      // Educational Articles Hungarian
      edu_article_1_title: "Miért veszítenek pénzt a Forex kereskedők | Fő okok",
      edu_article_1_text: "A pénzügyi kereskedés, beleértve a valutapiacokat, hosszú és részletes tervezést igényel több szinten. A kereskedés nem kezdődhet meg a kereskedő által a piac alapjainak megértése és a folyamatosan változó piaci környezet folyamatos elemzése nélkül. Azok számára, akik befektetésben és kereskedésben érdekeltek, olvassák el az alábbi javaslatokat, és megtudhatják, hogyan kerüljék el a pénzveszteséget a Forex kereskedésben.",

      edu_article_2_title: "Gyenge kockázatkezelés",
      edu_article_2_text: "A helytelen kockázatkezelés az egyik fő ok, amiért a Forex kereskedők gyorsan veszítenek pénzt. Nem véletlen, hogy a kereskedési platformok automatikus take-profit és stop-loss mechanizmusokkal vannak felszerelve. Ezek elsajátítása jelentősen javítja a kereskedő siker esélyeit. A kereskedőknek nem csak tudniuk kell, hogy ezek a mechanizmusok léteznek, hanem azt is, hogyan implementálják őket helyesen az adott időszakra és kereskedés időtartamára jellemző piaci volatilitási szinteknek megfelelően. Ne feledje, hogy a 'túl alacsonyan beállított stop-loss' likvidálhat egy olyan pozíciót, amely egyébként jövedelmező lehetett volna. Ugyanakkor a 'túl magasra beállított take-profit' nem érhető el a volatilitás hiánya miatt. A kockázat/jutalom arányok figyelembevétele is fontos része a jó kockázatkezelésnek.",

      edu_article_3_title: "Nem alkalmazkodás a piaci feltételekhez",
      edu_article_3_text: "Az a feltételezés, hogy egy bevált kereskedési stratégia elegendő lesz végtelen nyerő tranzakciók előállításához, az egyik ok, amiért a Forex kereskedők veszítenek pénzt. A piacok nem statikusak. Ha azok lennének, lehetetlen lenne velük kereskedni. Mivel a piacok folyamatosan változnak, a kereskedőnek kifejlesztenie kell azt a képességét, hogy nyomon kövesse ezeket a változásokat, és alkalmazkodjon minden előforduló helyzethez.<br/><br/>A jó hír az, hogy ezek a piaci változások nem csak új kockázatokat, hanem új kereskedési lehetőségeket is kínálnak. A képzett kereskedő értékeli a változásokat, ahelyett, hogy félne tőlük. Többek között a kereskedőnek meg kell tanulnia nyomon követni az átlagos volatilitást a pénzügyi hírek közzététele után, és képesnek kell lennie megkülönböztetni a trendpiacot a tartományos piactól.<br/><br/>A piaci volatilitás jelentős hatással lehet a kereskedési teljesítményre. A kereskedőknek tudniuk kell, hogy a piaci volatilitás órákra, napokra, hónapokra, sőt évekre is kiterjedhet. Sok kereskedési stratégia volatilitásfüggőnek tekinthető, és sokuk kevésbé hatékony eredményeket produkál a kiszámíthatatlanság időszakaiban. Ezért a kereskedőnek mindig meg kell győződnie arról, hogy az általa használt stratégia összhangban van az aktuális piaci feltételekben létező volatilitással.<br/><br/>A pénzügyi hírek közzétételei is fontosak a nyomon követéshez, még akkor is, ha a választott stratégia nem alapoz a fundamentalis elemzésre. A monetáris politikai döntések, mint például a kamatlábak változása, vagy még a munkanélküliségről vagy fogyasztói bizalomról szóló meglepő gazdasági adatok is drámaian megváltoztathatják a kereskedői közösség piaci hangulatát.<br/><br/>Ahogy a piac reagál ezekre az eseményekre, elkerülhetetlen hatás van a megfelelő valuták kínálatára és keresletére. Végül, a trendpiacok és tartományos piacok megkülönböztetésére való képtelenség gyakran azt eredményezi, hogy a kereskedők rossz kereskedési eszközöket alkalmaznak rossz időpontban.",

      edu_article_4_title: "Mi a kockázat-jutalom arány?",
      edu_article_4_text: "A kockázat/jutalom arány (Risk/Reward Ratio vagy RR) egyszerűen egy beállított mérés, amely segít a kereskedőknek tervezni, mennyi profitot érnek el, ha a tranzakció a várt módon alakul, vagy mennyit veszítenek, ha nem. Fontolja meg ezt a példát. Ha a 'take-profit' 100 pip-re van beállítva, és a stop-loss 50 pip-re, a kockázat/jutalom arány 2:1. Ez azt is jelenti, hogy legalább minden harmadik kereskedésnél nullára jössz ki, feltéve, hogy jövedelmezőek. A kereskedőknek mindig együtt kell ellenőrizniük ezt a két változót, hogy biztosak legyenek abban, hogy illeszkednek a profit célokhoz. A legjobb módja a kockázatok teljes elkerülésének a Forex kereskedésben egy ingyenes demo kereskedési számla használata. Demo számlával kereskedhet anélkül, hogy a tőkéjét kockáztatná, miközben még mindig a legfrissebb valós idejű kereskedési információk és elemzések használatára számíthat. Ez a legjobb hely a kereskedőknek a kereskedés megtanulásához, és a haladó kereskedőknek az új stratégiák gyakorlásához. Ha érdekel a kipróbálása, kattintson az alábbi bannerre!",

      // Additional Hungarian translations
      edu_read_more_articles: "Olvass további cikkeket",
      edu_intermediate_articles_title: "Középhaladó cikkek",
      edu_intermediate_articles_text: "Íme 6 legjobb cikkünk középhaladó kereskedők számára, a legjobb eszközökkel és technikákkal, amelyek segítenek optimalizálni kereskedési stratégiádat és idődet hatékonyabban kezelni.<br/>Valami mást keresel? Tekintsd meg az összes cikket.",

      // Accounts Page Hungarian translations
      accounts_hero_title: "Tanulj meg kereskedni a <span>Quantum Prime Finance CHAMBER</span> segítségével",
      accounts_hero_subtitle: "Szerezz tudást a Quantum Prime Finance CHAMBER-től",

      // Article 1 Page Hungarian translations - "A 20 legjobb Forex kereskedési eszköz 2023-hoz"
      art1_page_title: "Oktatás",
      art1_page_subtitle: "A 20 legjobb Forex kereskedési eszköz 2023-hoz",
      
      art1_section1_title: "Hogyan találj kereskedési lehetőségeket",
      art1_section1_text: "Ez az első eszközcsoport a Forex kereskedéshez. A Forex kereskedők bizonyos eszközöket használnak kereskedési lehetőségek megtalálására. A legjobb Forex kereskedési eszközök listánk a kereskedési lehetőségek megtalálásához az alábbi alkategóriákban található eszközöket tartalmazza:",
      art1_section1_item1: "Forex naptárak",
      art1_section1_item2: "Kereskedési hírforrások",
      art1_section1_item3: "Műszaki elemzési eszközök",
      art1_section1_item4: "Ötletgeneráló eszközök",

      art1_section2_title: "Forex naptárak: Közgazdasági naptárak, hírnaptárak, vállalati naptárak és egyebek",
      art1_section2_text: "A Forex naptárak értékes eszközök annak megtudásához, hogy mi fog történni a piacon, és ennek megfelelően tervezhetik kereskedéseiket.<br/><br/>Főként azoknak a kereskedőknek szólnak, akik naprakészen szeretnének maradni az FX piac alapvető frissítéseivel. Azonban ezek a naptárak minden típusú kereskedő számára hasznosak lehetnek.<br/><br/>Az Admirals ingyenes Forex naptára felsorolja a közelgő alapvető eseményeket és gazdasági hírek közzétételét, korábbi és várható értékeikkel együtt. Amint a hírek közzétételre kerülnek (néha kis késleltetéssel), a naptár a megfelelő értékekkel frissül, és a piac új mozgásokat kezd tapasztalni.",

      art1_section3_title: "Élő FX hírek: Kereskedési hírek, élő piaci hírek, Forex hírforrások és egyebek",
      art1_section3_text: "A közgazdasági naptárak mellett az élő hírek egy másik hasznos Forex kereskedési eszköz. A probléma az, hogy olyan sok információt kell átnézni! Bár manuálisan kereshetsz a Bloomberg, Reuters, Forex Factory és FX street oldalakon, nem lenne könnyebb, ha minden automatikusan hozzájuk jutna?<br/><br/>A jó hír az, hogy ez lehetséges. Valójában az Admirals MetaTrader Supreme Edition bővítmény tartalmazza az Admiral Connect nevű funkciót, amely élő kereskedési híreket szállít közvetlenül a MetaTrader terminálodhoz!<br/><br/>Az Admiral Connect eszköz összeköti az összes nagy hírszolgáltatót, beleértve egy kereskedési elemzési funkciót és saját RSS-hírforrásaidat a platformon belüli megjelenítéshez, és kevesebb időt töltesz a terminálon kívül, amikor a kereskedéshez szükséges további funkciókat és információkat keresel.",

      art1_section4_title: "Műszaki elemzés Forex kereskedéshez",
      art1_section4_text: "Sok kereskedő vonzónak találja a műszaki elemzést, mert lehetővé teszi számukra az FX kereskedési lehetőségek azonosítását anélkül, hogy naprakészen kellene maradniuk a piaci hírekkel. A fő kihívás azonban a megbízható kereskedési jeleket generáló pontos mintázatok azonosítása.<br/><br/>A jó hír az, hogy sok elérhető Forex kereskedési eszköz elvégzi ezt a munkát helyetted.<br/><br/>Az első, amelyet tárgyalunk, az Admirals Premium Analytics portáljának Műszaki Elemzés és Kereskedési Jelek funkciója. A Trading Central díjnyertes technológiájával működő widget akcióképes műszaki elemzést nyújt szinte minden pénzügyi instrumentumhoz (beleértve részvényeket és Forex-et), hogy segítsen a befektetőknek optimalizálni kereskedési stratégiáikat.",

      // Article 2 Page Hungarian translations - "3 Pivot Point Forex kereskedési stratégia"
      art2_page_title: "Oktatás",
      art2_page_subtitle: "3 Pivot Point Forex kereskedési stratégia",
      
      art2_section1_title: "Mi az a pivot pont?",
      art2_section1_text: "Mi az a pivot pont a Forex-ben? A pivot pontok segítik a kereskedőket a pénzügyi piacokon bekövetkező ármozgások meghatározásában.<br/><br/>Egyszerűen fogalmazva, a pivot pont egy árszint, amelyet a professzionális kereskedők használnak annak értékelésére, hogy az árak emelkedő vagy csökkenő tendenciájúak. A pivot pontok az átlagokat jelentik a maximumok, minimumok és záróárak számára, amelyek egy kereskedési munkamenetben vagy kereskedési napon belül fordulnak elő. A pivot pontok a műszaki elemzéshez használt mutatók egy fajtája, amelyek alapot adnak a piaci trendek meghatározásához.",

      art2_section2_title: "Támogatási és ellenállási szintek pivot pont kereskedésben",
      art2_section2_text: "Szinte minden műszaki elemzési forma alapján a támogatás és ellenállás alapvető fogalmai állnak. Úgy gondolhatunk rájuk, mint olyan szintekre, amelyeket kulcsfontosságú csataterekként várunk a medvék és bikák közötti harcban. Ahogy a piac közeledik hozzájuk, egyes kereskedők az ár visszapattanását várják.<br/><br/>Mások a kitörés lehetőségét sejthetik meg. Következésképpen ezek fontos árak, mert egy jelentős mozgás lehetőségét jelezik. Ezért nagyon hasznos képesség a fejlesztéshez annak azonosítása, hogy ezek a szintek hol helyezkednek el.<br/><br/>A támogatási és ellenállási pontok fontosságát figyelembe véve természetes kérdés merül fel: Hogyan számoljuk ki, hol találjuk meg ezeket a kulcsfontosságú árszinteket? Számos módszer létezik, amelyek megpróbálják kielégíteni ezt a kérést.<br/><br/>Az egyik népszerű technika a pivot pontok vizsgálata. A pivot pont kereskedés standard ár-információkat vesz, mint például a maximumok, minimumok és zárások, és ezeket az információkat használja a lehetséges támogatási és ellenállási szintek vetítéséhez.<br/><br/><strong>Ingyenes élő kereskedési webináriumok az Admirals-szel</strong><br/><br/>Ha érdekel a legjobb kereskedési mutatók, legnépszerűbb stratégiák, legfrissebb hírek, trendek és piaci fejlesztések megismerése és még sok más, nincs jobb módja ennek, mint az Admirals rendszeres INGYENES kereskedési webináriumai.<br/><br/>Készítés utasításokat kap lépésről lépésre a legjobb stratégiák és mutatók használatához, valamint szakértői véleményeket a legfrissebb eseményekről az élő piacokon. Kattintson az alábbi bannerre, hogy regisztráljon az INGYENES kereskedési webináriumokra!",

      art2_section3_title: "Forex Kereskedő: Bevezetés",
      art2_section3_text: "Egy kereskedő valaki, aki rendeléseket ad le a pénzügyi piacon. Ez lehet pénzügyi intézmények nevében, mint például nagy bankok, befektetési alapok és hedge alapok, vagy független kereskedőként.<br/><br/>A tőzsdei rendelések, mint például részvények vásárlása vagy eladása, vagy a kereskedő nevében, vagy az ügyfelek nevében, vagy az őket foglalkoztató pénzügyi intézmény vagy bróker számára történnek. További kategorizálás lehetséges a kereskedett eszközöktől függően: Forex, részvények, kötvények, árucikkek stb.<br/><br/>Azok a kereskedők, akik pénzügyi intézményeknek vagy brókereknek dolgoznak, munkáltatójuk ügyfelei nevében vásárolnak és adnak el részvényeket, nem saját pénzükből. Ez azt jelenti, hogy ahelyett, hogy tényleges kereskedésükben nyereséget vagy veszteséget érnének el, kereskedőként fizetést keresnek. Ebben az esetben a kereskedő gyakorlatilag nem vállal kockázatot a piacon - az ügyfelükön múlik a pénzügyi instrumentumok vásárlása vagy eladása a kockázat fedezésére. A kereskedő ügyfelei lehetnek egyének vagy vállalatok, akiknek nincs saját kereskedési termük.<br/><br/>Azok, akik saját személyes számlájukon kereskednek, saját pénzüket használják, hogy maguknak próbáljanak profitot keresni. Ezek a számlák személyes forrásaikból vannak finanszírozva, és a tranzakciók online kereskedési platformokon keresztül kerülnek végrehajtásra. Annak ellenére, hogy az online brókerek tőkeáttételt kínálnak, a házi kereskedők által kereskedett összegek sokkal kisebbek, mint a professzionális kereskedőké. Mivel az online kereskedés gyakran az OTC (Over the Counter) piacon történik, a kereskedők saját számláikon elért sikere csak becslés.",

      // Article 3 Page Hungarian translations - "Hogyan válj Forex kereskedővé"
      art3_page_title: "Oktatás",
      art3_page_subtitle: "Hogyan válj Forex kereskedővé",
      
      art3_section1_title: "Forex Kereskedő: Bevezetés",
      art3_section1_text: "Egy kereskedő valaki, aki rendeléseket ad le a pénzügyi piacon. Ez lehet pénzügyi intézmények nevében, mint például nagy bankok, befektetési alapok és hedge alapok, vagy független kereskedőként.<br/><br/>A tőzsdei rendelések, mint például részvények vásárlása vagy eladása, vagy a kereskedő nevében, vagy az ügyfelek nevében, vagy az őket foglalkoztató pénzügyi intézmény vagy bróker számára történnek. További kategorizálás lehetséges a kereskedett eszközöktől függően: Forex, részvények, kötvények, árucikkek stb.<br/><br/>Azok a kereskedők, akik pénzügyi intézményeknek vagy brókereknek dolgoznak, munkáltatójuk ügyfelei nevében vásárolnak és adnak el részvényeket, nem saját pénzükből. Ez azt jelenti, hogy ahelyett, hogy tényleges kereskedésükben nyereséget vagy veszteséget érnének el, kereskedőként fizetést keresnek. Ebben az esetben a kereskedő gyakorlatilag nem vállal kockázatot a piacon - az ügyfelükön múlik a pénzügyi instrumentumok vásárlása vagy eladása a kockázat fedezésére. A kereskedő ügyfelei lehetnek egyének vagy vállalatok, akiknek nincs saját kereskedési termük.<br/><br/>Azok, akik saját személyes számlájukon kereskednek, saját pénzüket használják, hogy maguknak próbáljanak profitot keresni. Ezek a számlák személyes forrásaikból vannak finanszírozva, és a tranzakciók online kereskedési platformokon keresztül kerülnek végrehajtásra. Annak ellenére, hogy az online brókerek tőkeáttételt kínálnak, a házi kereskedők által kereskedett összegek sokkal kisebbek, mint a professzionális kereskedőké. Mivel az online kereskedés gyakran az OTC (Over the Counter) piacon történik, a kereskedők saját számláikon elért sikere csak becslés.",

      art3_section2_title: "A siker meghatározása Forex kereskedőként",
      art3_section2_intro: "Most, hogy már tudod, mi az a kereskedő, hogyan válhatsz Forex kereskedővé? És aztán, hogyan érhetsz el benne sikereket?<br/><br/>A kereskedés megkezdésekor fontos megérteni, mit szeretnél elérni belőle, és hogyan definiálod a sikert.",
      art3_section2_heading: "Dolgok, amelyekre figyelni kell, amikor elkezdesz Forex-en kereskedni:",
      art3_section2_point1: "Tűzz ki magadnak egy reális és mérhető célt. Ez lehet valami olyasmi, mint a 20% éves hozam elérése a befektetéseden, vagy 100 pip összesen elérése havonta.",
      art3_section2_point2: "A célodnak könnyen mérhetőnek is kell lennie.",
      art3_section2_point3: "Tűzz ki egy olyan célt, amely hosszú távon érhető el - éves cél kitűzését javasoljuk a havi helyett.",
      art3_section2_middle: "Miután kitévezted a fő kereskedési célodat az évre, itt az ideje elkezdeni tanulni, hogyan érheted el.",
      art3_section2_question1: "Azonosítsd, milyen erőforrások állnak rendelkezésedre.",
      art3_section2_question2: "Mennyi pénzt tudsz használni kezdeti betétként?",
      art3_section2_question3: "Szeretnél teljes munkaidős Forex kereskedővé válni? Vagy csak hétvégén szeretnél kereskedni?",
      art3_section2_conclusion: "Ezek közül néhány kérdés, amelyet fel kellene tenned magadnak.<br/><br/>Miután már van egy világos képed, itt az ideje cselekvési terved elkészítésének. Ennek a tervnek tartalmaznia kell azokat a valutapárokat, amelyeken tervezed kereskedni, valamint a napi kereskedések számát, amelyeket elkötelezel magadat.<br/><br/>Ez kissé túlnyomó lehet az új kereskedők számára, így a jó hír az, hogy ebben a cikkben megosztjuk a 10 legjobb tippünket, amelyek segítenek megtanulni, hogyan válhatsz sikeres Forex kereskedővé.<br/><br/>Ha kezdő kereskedő vagy, aki keres egy helyet, ahol megtanulhatod a Forex kereskedés titkait, nyugodtan csatlakozz élő, ingyenes webináriumainkhoz az alábbi bannerre kattintva:",
    
// --- AUTO-GENERATED (articles) ---

},
  };

  // Helpers
  const getParamLang = () => {
    const m = location.search.match(/[?&]lang=(en|hu)\b/i);
    return m ? m[1].toLowerCase() : null;
  };

  function setLang(lang) {
    const final = (lang && dict[lang]) ? lang : DEFAULT_LANG;
    localStorage.setItem("site_lang", final);
    applyTranslations(final);
    document.documentElement.setAttribute("lang", final);
    updateLinksWithLanguage(final);
  }

  function currentLang() {
    return localStorage.getItem("site_lang") || getParamLang() || DEFAULT_LANG;
  }

  function applyTranslations(lang) {
    const t = dict[lang] || {};
    // Translate text nodes
    document.querySelectorAll("[data-i18n]").forEach(el => {
      const key = el.getAttribute("data-i18n");
      if (!key) return;
      if (t[key] !== undefined) {
        // Use innerHTML to allow <span> in hero title
        el.innerHTML = t[key];
      }
    });

    // Translate placeholders
    document.querySelectorAll("[data-i18n-ph]").forEach(el => {
      const key = el.getAttribute("data-i18n-ph");
      if (t[key] !== undefined) {
        el.setAttribute("placeholder", t[key]);
      }
    });

    // Mark active lang in menus (optional)
    document.querySelectorAll("[data-setlang]").forEach(btn => {
      const isActive = btn.getAttribute("data-setlang") === lang;
      btn.classList.toggle("active", isActive);
    });

    // Optional: update TradingView locale if present
    try {
      const locale = lang === "hu" ? "hu" : "en";
      document.querySelectorAll('script[src*="tradingview.com"]').forEach(s => {
        // Can't reconfigure inline widget easily without reloading.
        // For initial load, prefer setting "locale" in the widget config.
      });
      // If you want full re-init, reload with ?lang=...
      // location.search = "?lang=" + lang;
    } catch (e) {}
  }

  function updateLinksWithLanguage(lang) {
    // Update all internal HTML page links to include language parameter
    document.querySelectorAll('a[href]').forEach(a => {
      const href = a.getAttribute("href");
      
      // Skip external links, anchors, javascript, mailto, etc.
      if (!href || href.startsWith('http') || href.startsWith('//') || 
          href.startsWith('#') || href.startsWith('javascript:') || 
          href.startsWith('mailto:') || href.startsWith('tel:')) {
        return;
      }
      
      // Only process .html files or relative paths that look like pages
      if (href.includes('.html') || href === '/' || href === './') {
        try {
          // Handle relative URLs properly
          let newHref = href;
          
          // Remove existing lang parameter if present
          if (newHref.includes('?lang=')) {
            newHref = newHref.replace(/[?&]lang=[^&]*(&|$)/, '$1').replace(/[?&]$/, '');
          }
          
          // Add the language parameter
          const separator = newHref.includes('?') ? '&' : '?';
          newHref += separator + 'lang=' + lang;
          
          a.setAttribute("href", newHref);
        } catch (e) {
          // Fallback: just add lang parameter
          const separator = href.includes('?') ? '&' : '?';
          a.setAttribute("href", href + separator + 'lang=' + lang);
        }
      }
    });
  }

  // Wire up listeners
  document.addEventListener("click", (e) => {
    const trg = e.target.closest("[data-setlang]");
    if (trg) {
      e.preventDefault();
      setLang(trg.getAttribute("data-setlang"));
    }
  });

  // On DOM ready, apply language
  document.addEventListener("DOMContentLoaded", () => {
    const lang = getParamLang() || localStorage.getItem("site_lang") || DEFAULT_LANG;
    setLang(lang);
  });

  // Expose for debugging
  window._i18n = { setLang, currentLang, dict };
})();
