# CodeIgniter Pagination Mod
Extend CodeIgniter Pagination Library that can be implemented for searching applications

---

# Implementation

	// Load Pagination Library
	$this->load->library('pagination');

	// Get Search Keyword (By POST or SEGMENT)
	if ( $this->uri->segment(3) ) {
		$keyword	= $this->uri->segment(3);
	} else {
		$keyword	= $this->input->post('keyword');
	}

	// Pagination Config
	$config['base_url']	= $this->config->site_url() . '/your_controller/your_method/';
	$config['per_page']	= 30;
	$config['query_url']	= $keyword;

	// Set Select Query with "SQL_CALC_FOUND_ROWS" method
	$this->db->select('SQL_CALC_FOUND_ROWS, your_db_table.*', FALSE)

	// Set Limit Query for paging 
	$this->db->limit( $config['per_page'], *$this->pagination->first_record(3, $config['per_page']) );

	// Run Query
	$query	= $this->db->get('your_db_table');

	// Get Total records from query
	$config['total_rows']	= $this->pagination->found_rows();
	
	// Initialize Pagination
	$this->pagination->initialize($config); 
	
	// Set output variable
	$data['query']		= $query;
	$data['navigation']	= $this->pagination->create_links();

	// Load View
	$this->load->view('your_view_path', $data);
	
---

# Output
  http://www.your-domain.com/your_controller/your_method/page_number/keyword
