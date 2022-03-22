/* =================================================================
    Line chart
================================================================= */

// var ctx = document.getElementById("line");
//
// var data = {
//     labels: ["June", "July", "August", "September", "October", "November", "December"],
//     datasets: [
//         {
//             label: "Debits",
//             fill: false,
//             lineTension: 0.0,
//             backgroundColor: "#f44236", //#f44236
//             borderColor: "#f44236",
//             borderCapStyle: 'butt',
//             borderDash: [],
//             borderDashOffset: 0.0,
//             borderJoinStyle: 'miter',
//             pointBorderColor: "#f44236",
//             pointBackgroundColor: "#fff",
//             pointBorderWidth: 1,
//             pointHoverRadius: 5,
//             pointHoverBackgroundColor: "#f44236",
//             pointHoverBorderColor: "#fff",
//             pointHoverBorderWidth: 2,
//             pointRadius: 1,
//             pointHitRadius: 10,
//             data: [10, 90, 20, 65, 42, 54, 120],
//             spanGaps: false,
//         },
//         {
//             label: "Credits",
//             fill: false,
//             lineTension: 0.0,
//             backgroundColor: "#3e70c9", //#f44236
//             borderColor: "#3e70c9",
//             borderCapStyle: 'butt',
//             borderDash: [],
//             borderDashOffset: 0.0,
//             borderJoinStyle: 'miter',
//             pointBorderColor: "#3e70c9",
//             pointBackgroundColor: "#fff",
//             pointBorderWidth: 1,
//             pointHoverRadius: 5,
//             pointHoverBackgroundColor: "#3e70c9",
//             pointHoverBorderColor: "#fff",
//             pointHoverBorderWidth: 2,
//             pointRadius: 1,
//             pointHitRadius: 10,
//             data: [40, 10, 45, 22, 34, 90, 89],
//             spanGaps: false,
//         }
//     ]
// };
//
// var myChart = new Chart(ctx, {
//     type: 'line',
//     data: data,
//     options: {
//         scales: {
//             yAxes: [{
//                 ticks: {
//                     beginAtZero:true
//                 }
//             }]
//         }
//     }
// });

/* =================================================================
    Bar chart
================================================================= */

var ctx = document.getElementById("bar");

var data = {
    labels: ["June", "July", "August", "September", "October", "November", "December"],
    datasets: [
    {
        label: 'Pending',
        data: [8,88,88,99],
        backgroundColor: '#3e70c9',
        borderColor: '#3e70c9',
        borderWidth: 1
    },
    {
        label: 'Paid',
        data: [3,4,8,8],
        backgroundColor: 'green',
        borderColor: '#f59345',
        borderWidth: 1
    },
    {
        label: 'Defaulted',
        data: [98,44,55],
        backgroundColor: '#f44236',
        borderColor: '#f44236',
        borderWidth: 1
    }
   ]
};

var myChart = new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

/* =================================================================
    Pie chart
================================================================= */

var ctx = document.getElementById("pie");

 var data = {
    labels: [
        "Active",
        "Inactive",
        "Verified",
        "Unverified",
        "All"
    ],
    datasets: [{
        data: [2, 5, 10, 1, 18],
        backgroundColor: [
            "#3e70c9",
            "#f59345",
            "#f44236",
            "#43B968",
            "#A567E2"
        ]
    }]
};

var myChart = new Chart(ctx, {
    type: 'pie',
    data: data
});

/* =================================================================
    Doughnut chart
================================================================= */

var ctx = document.getElementById("doughnut");

 var data = {
    labels: [
        "All",
        "Active",
        "Inactive"
    ],
    datasets: [{
        data: [29, 13, 16],
        backgroundColor: [
            "#3e70c9",
            "#f59345",
            "#f44236"
        ]
    }]
};

var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: data
});

 /* Multiple lines chart */
  Morris.Area({
      element: 'multiple',
      data: [{
          period: '2010',
          adidas: 120,
          nike: 110,
          lacoste: 40
      }, {
          period: '2011',
          adidas: 180,
          nike: 130,
          lacoste: 170
      }, {
          period: '2012',
          adidas: 120,
          nike: 170,
          lacoste: 100
      }, {
          period: '2013',
          adidas: 90,
          nike: 130,
          lacoste: 40
      }, {
          period: '2014',
          adidas: 120,
          nike: 150,
          lacoste: 70
      }, {
          period: '2015',
          adidas: 60,
          nike: 70,
          lacoste: 90
      },
       {
          period: '2016',
          adidas: 170,
          nike: 190,
          lacoste: 140
      }],
      xkey: 'period',
      ykeys: ['adidas', 'nike', 'lacoste'],
      labels: ['Adidas', 'Nike', 'Lacoste'],
      pointSize: 3,
      fillOpacity: 0,
      pointStrokeColors:['#f44236', '#43b968', '#20b9ae'],
      behaveLikeLine: true,
      gridLineColor: '#e0e0e0',
      lineWidth: 1,
      hideHover: 'auto',
      lineColors: ['#f44236', '#43b968', '#20b9ae'],
      resize: true
  });
