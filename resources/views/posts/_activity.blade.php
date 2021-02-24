<x-card :title="'Most Active Users Last Month'" :subtitle="'Most Active Users Last Month'" :items="collect($mostActiveLastMonth)->pluck('email')"></x-card>
<x-card :title="'Most Active Users'" :subtitle="'Most Active Users'" :items="collect($mostActive)->pluck('email')"></x-card>
<x-card :title="'Most Commented'" :subtitle="'Most commented'" :items="collect($mostCommented)->pluck('title')"></x-card>
