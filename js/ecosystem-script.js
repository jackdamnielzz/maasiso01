document.addEventListener('DOMContentLoaded', () => {
    initEcosystem();
});

function initEcosystem() {
    createTree();
    createISOStandards();
    createIndustryRoots();
    setupParallax();
    setupScrollAnimation();
}

function createTree() {
    const svg = d3.select("#tree-svg");
    const width = svg.node().getBoundingClientRect().width;
    const height = svg.node().getBoundingClientRect().height;

    // Create trunk
    const trunk = svg.append("path")
        .attr("class", "tree-trunk")
        .attr("d", `M${width/2},${height} L${width/2},${height*0.6}`)
        .attr("stroke-dasharray", function() { return this.getTotalLength() })
        .attr("stroke-dashoffset", function() { return this.getTotalLength() });

    // Animate trunk growth
    trunk.transition()
        .duration(2000)
        .attr("stroke-dashoffset", 0);

    // Create main branches
    const branches = [
        {start: [width/2, height*0.6], end: [width/2 - 100, height*0.5]},
        {start: [width/2, height*0.6], end: [width/2 + 100, height*0.5]},
        {start: [width/2, height*0.7], end: [width/2 - 80, height*0.6]},
        {start: [width/2, height*0.7], end: [width/2 + 80, height*0.6]}
    ];

    branches.forEach((branch, index) => {
        svg.append("path")
            .attr("class", "tree-branch")
            .attr("d", `M${branch.start[0]},${branch.start[1]} L${branch.end[0]},${branch.end[1]}`)
            .attr("stroke-dasharray", function() { return this.getTotalLength() })
            .attr("stroke-dashoffset", function() { return this.getTotalLength() })
            .transition()
            .delay(2000 + index * 500)
            .duration(1000)
            .attr("stroke-dashoffset", 0);
    });
}

function createISOStandards() {
    const svg = d3.select("#tree-svg");
    const width = svg.node().getBoundingClientRect().width;
    const height = svg.node().getBoundingClientRect().height;

    const standards = document.querySelectorAll('.iso-standard');
    const standardsArray = Array.from(standards);

    standardsArray.forEach((standard, index) => {
        const angle = (index / standardsArray.length) * Math.PI * 2;
        const x = width/2 + Math.cos(angle) * width * 0.3;
        const y = height/2 + Math.sin(angle) * height * 0.3;

        standard.style.left = `${x}px`;
        standard.style.top = `${y}px`;

        // Create branch
        const branch = svg.append("path")
            .attr("class", "iso-branch")
            .attr("d", `M${width/2},${height*0.6} L${x},${y}`)
            .attr("stroke-dasharray", function() { return this.getTotalLength() })
            .attr("stroke-dashoffset", function() { return this.getTotalLength() });

        // Create leaf
        const leaf = svg.append("circle")
            .attr("class", "iso-leaf")
            .attr("cx", x)
            .attr("cy", y)
            .attr("r", 10);

        // Animate branch and leaf on hover
        standard.addEventListener('mouseenter', () => {
            branch.style("opacity", 1)
                .transition()
                .duration(500)
                .attr("stroke-dashoffset", 0);
            leaf.style("opacity", 1);
        });

        standard.addEventListener('mouseleave', () => {
            branch.style("opacity", 0)
                .attr("stroke-dashoffset", function() { return this.getTotalLength() });
            leaf.style("opacity", 0);
        });
    });
}

function createIndustryRoots() {
    const svg = d3.select("#tree-svg");
    const width = svg.node().getBoundingClientRect().width;
    const height = svg.node().getBoundingClientRect().height;

    const roots = document.querySelectorAll('.industry-root');
    const rootsArray = Array.from(roots);

    rootsArray.forEach((root, index) => {
        const x = (index + 1) * (width / (rootsArray.length + 1));
        const y = height;

        // Create root path
        const rootPath = svg.append("path")
            .attr("class", "root-path")
            .attr("d", `M${width/2},${height*0.9} Q${(width/2 + x)/2},${height*0.95} ${x},${y}`)
            .attr("stroke-dasharray", function() { return this.getTotalLength() })
            .attr("stroke-dashoffset", function() { return this.getTotalLength() });

        // Add click event to root element
        root.addEventListener('click', () => {
            // Here you can add the logic to navigate to industry-specific pages
            console.log(`Clicked on ${root.dataset.industry} industry`);
        });
    });
}

function setupParallax() {
    const container = document.getElementById('ecosystem-container');
    container.addEventListener('scroll', () => {
        const scrollY = container.scrollTop;
        document.getElementById('tree-svg').style.transform = `translateZ(-1px) scale(2) translateY(${scrollY * 0.5}px)`;
    });
}

function setupScrollAnimation() {
    const container = document.getElementById('ecosystem-container');
    const rootPaths = document.querySelectorAll('.root-path');

    container.addEventListener('scroll', () => {
        const scrollPercentage = container.scrollTop / (container.scrollHeight - container.clientHeight);

        rootPaths.forEach((rootPath) => {
            const length = rootPath.getTotalLength();
            const drawLength = length * scrollPercentage;
            rootPath.style.strokeDashoffset = length - drawLength;
            rootPath.style.opacity = scrollPercentage;
        });
    });
}

// We'll implement other functions as we develop the page
